<template>
  <AuthenticatedLayout>
    <Head title="Order Details" />

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
      <h2 class="text-2xl font-bold mb-2">Order #{{ order.id }}</h2>

      <!-- Общая информация о заказе -->
      <div class="mb-4">
        <p><strong>Job Title:</strong> {{ order.job_application.job_ad.Title }}</p>
        <p><strong>Client:</strong> {{ order.client.name }}</p>
        <p><strong>Freelancer:</strong> {{ order.freelancer.name }}</p>
      </div>

      <!-- Если пользователь - клиент -->
      <div v-if="auth.user?.id === order.client_id">
        <h3 class="font-bold mb-2 text-lg">Status</h3>
        <div class="mb-4">
          <template v-if="order.status === 'in_progress'">
            <p class="text-gray-700">Freelancer is currently working on your project.</p>
          </template>
          <template v-else-if="order.status === 'submitted'">
            <p class="text-gray-700">
              Freelancer has submitted the work. Please review it below.
            </p>
          </template>
          <template v-else-if="order.status === 'completed'">
            <p class="text-green-600 font-semibold">This order is already completed.</p>
          </template>
          <template v-else-if="order.status === 'cancelled'">
            <p class="text-red-600 font-semibold">This order was cancelled.</p>
          </template>
        </div>

        <!-- Если фрилансер отправил работу, показываем результат -->
        <div v-if="order.status === 'submitted'">
          <h3 class="font-bold mb-2 text-lg">Submitted Work</h3>
          <p><strong>Description:</strong> {{ order.result_text }}</p>
          <div class="mt-2">
            <a
              v-if="order.result_file"
              :href="order.result_file"
              target="_blank"
              class="text-blue-600 hover:underline"
            >
              Download Attached File
            </a>
          </div>

          <!-- Кнопка завершения заказа (клиент подтверждает) -->
          <form @submit.prevent="completeOrder" class="mt-4">
            <button
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
              Mark as Complete
            </button>
          </form>
        </div>
      </div>

      <!-- Если пользователь - фрилансер -->
      <div v-else-if="auth.user?.id === order.freelancer_id">
        <h3 class="font-bold mb-2 text-lg">Status</h3>
        <div class="mb-4">
          <template v-if="order.status === 'in_progress'">
            <p class="text-gray-700">You are currently working on this project.</p>
          </template>
          <template v-else-if="order.status === 'submitted'">
            <p class="text-gray-700">
              You have submitted your work and are awaiting client's confirmation.
            </p>
          </template>
          <template v-else-if="order.status === 'completed'">
            <p class="text-green-600 font-semibold">This order is already completed.</p>
          </template>
          <template v-else-if="order.status === 'cancelled'">
            <p class="text-red-600 font-semibold">This order was cancelled.</p>
          </template>
        </div>

        <!-- Фрилансер может отправить работу, если статус in_progress -->
        <div v-if="order.status === 'in_progress'">
          <h2 class="text-lg font-bold mb-2">Submit Your Work</h2>
          <form @submit.prevent="submitWork" class="space-y-4">
            <textarea
              v-model="resultText"
              placeholder="Describe your work"
              class="w-full p-2 border rounded"
              required
            ></textarea>
            <input type="file" @change="handleFileUpload" />

            <button
              type="submit"
              class="btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            >
              Submit Work
            </button>
          </form>
        </div>

        <div v-else-if="order.status === 'submitted'">
          <h3 class="font-bold mb-2 text-lg">Your Submitted Work</h3>
          <p class="mb-2"><strong>Description:</strong> {{ order.result_text }}</p>
          <div>
            <a
              v-if="order.result_file"
              :href="order.result_file"
              target="_blank"
              class="text-blue-600 hover:underline"
            >
              Download Attached File
            </a>
          </div>
        </div>
      </div>

      <div v-else>
        <p class="text-gray-700">You do not have permission to view this order.</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const { props } = usePage()
const order = props.order
const auth = props.auth || { user: null }

const resultText = ref('')
const resultFile = ref(null)

const handleFileUpload = (e) => {
  resultFile.value = e.target.files[0]
}

const submitWork = async () => {
  const formData = new FormData()
  formData.append('result_text', resultText.value)
  if (resultFile.value) {
    formData.append('result_file', resultFile.value)
  }

  await axios
    .post(route('orders.submit-work', order.id), formData)
    .then(() => {
      alert('Work submitted successfully!')
      location.reload()
    })
    .catch((error) => {
      console.error(error)
      alert('Failed to submit work.')
    })
}

const completeOrder = async () => {
  await axios
    .post(route('orders.complete', order.id))
    .then(() => {
      alert('Order completed!')
      location.reload()
    })
    .catch((error) => {
      console.error(error)
      alert('Failed to complete order.')
    })
}
</script>
