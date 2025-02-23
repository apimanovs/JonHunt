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

      <!-- ЕСЛИ ПОЛЬЗОВАТЕЛЬ - КЛИЕНТ -->
      <div v-if="auth.user?.id === order.client_id">
        <h3 class="font-bold mb-2 text-lg">Status</h3>
        <div class="mb-4">
          <template v-if="order.status === 'in_progress'">
            <p class="text-gray-700">Freelancer is currently working on your project.</p>
          </template>
          <template v-else-if="order.status === 'submitted'">
            <p class="text-gray-700">Freelancer has submitted the work. Please review it below.</p>
          </template>
          <template v-else-if="order.status === 'completed'">
            <p class="text-green-600 font-semibold">This order is already completed.</p>
          </template>
          <template v-else-if="order.status === 'cancelled'">
            <p class="text-red-600 font-semibold">This order was cancelled.</p>
            <!-- Причина отмены, если есть -->
            <p v-if="order.cancel_reason" class="text-gray-700 mt-1">
              <strong>Reason:</strong> {{ order.cancel_reason }}
            </p>
          </template>
        </div>

        <!-- Показываем работу, если статус "submitted" -->
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

          <!-- Кнопка завершения заказа (Mark as Complete) -->
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

      <!-- ЕСЛИ ПОЛЬЗОВАТЕЛЬ - ФРИЛАНСЕР -->
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
            <!-- Отображаем причину, если есть -->
            <p v-if="order.cancel_reason" class="text-gray-700 mt-1">
              <strong>Reason:</strong> {{ order.cancel_reason }}
            </p>
          </template>
        </div>

        <!-- Если статус "in_progress" -> форма отправки работы -->
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
              class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            >
              Submit Work
            </button>
          </form>
        </div>

        <!-- Если статус "submitted" -> показываем то, что было отправлено -->
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

        <!-- Кнопка Cancel Order, если заказ еще не отменен и не завершен -->
        <div v-if="order.status !== 'completed' && order.status !== 'cancelled'">
          <button
            @click="showCancelForm = !showCancelForm"
            class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
          >
            Cancel Order
          </button>

          <div v-if="showCancelForm" class="mt-2">
            <form @submit.prevent="cancelOrder" class="space-y-2">
              <textarea
                v-model="cancelReason"
                placeholder="Reason for cancellation"
                class="w-full border border-gray-300 rounded p-2"
                rows="3"
              ></textarea>
              <button
                type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
              >
                Confirm Cancellation
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Если пользователь не клиент и не фрилансер -->
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

// Поля для фрилансера: отправка работы
const resultText = ref('')
const resultFile = ref(null)

// Поля для отмены заказа
const showCancelForm = ref(false)
const cancelReason = ref('')

// Загрузка файла
const handleFileUpload = (e) => {
  resultFile.value = e.target.files[0]
}

// Отправка работы (фрилансер)
const submitWork = async () => {
  const formData = new FormData()
  formData.append('result_text', resultText.value)
  if (resultFile.value) {
    formData.append('result_file', resultFile.value)
  }

  try {
    await axios.post(route('orders.submit-work', order.id), formData)
    alert('Work submitted successfully!')
    location.reload()
  } catch (error) {
    console.error(error)
    alert('Failed to submit work.')
  }
}

// Завершить заказ (клиент)
const completeOrder = async () => {
  try {
    await axios.post(route('orders.complete', order.id))
    alert('Order completed!')
    location.reload()
  } catch (error) {
    console.error(error)
    alert('Failed to complete order.')
  }
}

// Отменить заказ (фрилансер)
const cancelOrder = async () => {
  if (!cancelReason.value.trim()) {
    alert('Please provide a reason for cancellation.')
    return
  }

  try {
    await axios.post(route('orders.updateStatus', order.id), {
      status: 'cancelled',
      cancel_reason: cancelReason.value,
    })
    alert('Order has been cancelled.')
    location.reload()
  } catch (error) {
    console.error(error)
    alert('Failed to cancel order.')
  }
}
</script>
