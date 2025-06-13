<template>
  <AuthenticatedLayout>
    <Head title="Order Details" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-6">

        <!-- Left Column (Order Content) -->
        <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
          <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
            Order #{{ order.id }}
          </h2>

          <div class="mt-4" v-if="order.job_application">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">📌 Requirements</h3>
            <p class="whitespace-pre-line text-gray-800 dark:text-gray-300 leading-relaxed">
              {{ order.job_application.requirements }}
            </p>
          </div>
          <br>
          <!-- Project Link -->
          <div>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">📁 Project</h3>
            <template v-if="order.job_application">
              <a :href="`/gigs/${order.job_application.job_ad.id}`" class="text-lg text-gray-700 hover:text-red-600">
                {{ order.job_application.job_ad.Title }}
              </a>
            </template>
            <template v-else-if="order.project_application">
              <a :href="`/projects/${order.project_application.project.id}`" class="text-lg text-gray-700 hover:text-red-600">
                {{ order.project_application.project.title }}
              </a>
            </template>
            <template v-else>
              <span class="text-red-500">Unknown project type</span>
            </template>
          </div>

          <!-- Status Section -->
          <div class="mt-8">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">📊 Status</h3>
            <div class="p-4 border rounded-xl bg-gray-50 dark:bg-gray-700">
              <template v-if="order.status === 'in_progress'">
                <p class="text-gray-800 dark:text-gray-200">
                  Freelancer <span class="font-semibold">{{ order.freelancer.name }}</span> is currently working on your project.
                </p>
              </template>
              <template v-else-if="order.status === 'submitted'">
                <p class="text-gray-800 dark:text-gray-200">
                  Work submitted. Please review below.
                </p>
              </template>
              <template v-else-if="order.status === 'completed'">
                <p class="text-green-600 font-semibold">This order is completed. ✔️</p>
              </template>
              <template v-else-if="order.status === 'cancelled'">
                <p class="text-red-600 font-semibold">This order was cancelled. ❌</p>
                <p v-if="order.cancel_reason" class="text-gray-700 dark:text-gray-300 mt-1">
                  <strong>Reason:</strong> {{ order.cancel_reason }}
                </p>
              </template>
            </div>
          </div>

          <!-- Order Actions & Content -->
          <div class="mt-8 space-y-6">
            <!-- Client View -->
            <div v-if="auth.user?.id === order.client_id && (order.status === 'submitted' || order.status === 'completed')">
              <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">✅ Submitted Work</h3>
              <p class="text-gray-800 dark:text-gray-300"><strong>Description:</strong> {{ order.result_text }}</p>
              <a v-if="order.result_file" :href="order.result_file" target="_blank" class="text-blue-600 hover:underline font-medium">
                📂 Download Attached File
              </a>
              <form v-if="order.status === 'submitted'" @submit.prevent="completeOrder" class="mt-4">
                <button class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Mark as Complete</button>
              </form>
            </div>

            <!-- Freelancer View -->
            <div v-else-if="auth.user?.id === order.freelancer_id">
              <div v-if="order.status === 'submitted'">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">🔄 Update Submitted Work</h3>
                <form @submit.prevent="submitWork" class="space-y-4 mt-4">
                  <label class="block text-gray-700 dark:text-gray-300">Description:</label>
                  <textarea v-model="resultText" placeholder="Update your description..." class="w-full p-3 border rounded-lg text-gray-800 dark:text-gray-300" required></textarea>
                  <p v-if="order.result_file" class="text-sm text-gray-500">
                    Current file: <a :href="order.result_file" target="_blank" class="text-blue-600 underline">Download current</a>
                  </p>
                  <input type="file" @change="handleFileUpload" class="block" />
                  <button class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600">Update Work</button>
                </form>
              </div>
              <div v-else-if="order.status === 'in_progress'">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">🚀 Submit Your Work</h3>
                <form @submit.prevent="submitWork" class="space-y-4 mt-4">
                  <textarea v-model="resultText" placeholder="Describe your work..." class="w-full p-3 border rounded-lg text-gray-800 dark:text-gray-300" required></textarea>
                  <input type="file" @change="handleFileUpload" class="block" />
                  <button class="bg-green-500 text-white px-5 py-2 rounded-lg hover:bg-green-600">Submit Work</button>
                </form>
              </div>
              <div v-else-if="order.status === 'completed'">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">📄 Your Submitted Work</h3>
                <p class="text-gray-800 dark:text-gray-300"><strong>Description:</strong> {{ order.result_text }}</p>
                <a v-if="order.result_file" :href="order.result_file" target="_blank" class="text-blue-600 hover:underline font-medium">
                  📂 Download Attached File
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column (Chat) -->
        <div class="lg:w-1/3 bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 flex flex-col">
          <h3 class="text-2xl font-bold mb-4">💬 Chat</h3>
          <div ref="chatContainer" class="flex-1 max-h-[400px] overflow-y-auto border rounded-xl p-4 bg-gray-100">
            <div v-for="msg in messages" :key="msg.MessageID" class="mb-3" :class="msg.SenderID === auth.user.id ? 'text-right' : 'text-left'">
              <div class="inline-block max-w-[80%] p-3 rounded-2xl shadow" :class="msg.SenderID === auth.user.id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900'">
                <div class="text-sm font-semibold mb-1">{{ msg.sender.name }}</div>
                <div>{{ msg.Content }}</div>
                <div class="text-xs mt-1 text-right">{{ new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}</div>
              </div>
            </div>
          </div>
          <div class="mt-4 flex">
            <input type="text" v-model="chatMessage" placeholder="Type your message..." class="flex-1 p-3 border rounded-lg" @keyup.enter="sendMessage" />
            <button @click="sendMessage" class="ml-2 px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Send</button>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>


<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import axios from 'axios'
import Echo from 'laravel-echo'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const { props } = usePage()
const order = props.order
const auth = props.auth || { user: null }

const resultText = ref('')
const resultFile = ref(null)

const showCancelForm = ref(false)
const cancelReason = ref('')
const chatContainer = ref(null)

const chatMessage = ref('')
const messages = ref([])
const orderId = order.id


const handleFileUpload = (e) => {
  resultFile.value = e.target.files[0]
}

const scrollChatToBottom = () => {
  nextTick(() => {
    if (chatContainer.value) {
      chatContainer.value.scrollTop = chatContainer.value.scrollHeight
    }
  })
}

const loadMessages = async () => {
  const response = await axios.get(route('orders.messages', order.id))
  messages.value = response.data
  console.log(messages.value)
  scrollChatToBottom()
}

const sendMessage = async () => {
  if (chatMessage.value.trim() === '') return
  const receiverID = auth.user.id === order.client_id
    ? order.freelancer_id
    : order.client_id

  const response = await axios.post(route('messages.store'), {
    order_id: order.id,
    Content: chatMessage.value,
    ReceiverID: receiverID,
  })

  chatMessage.value = ''
}

onMounted(() => {
  loadMessages()

  if (order.result_text && auth.user?.id === order.freelancer_id) {
    resultText.value = order.result_text
  }

  const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    wsHost: window.location.hostname,
    wsPort: 6001,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: false,
    disableStats: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
      }
    }
  })

  const channel = pusher.subscribe(`private-order.${order.id}`)

  channel.bind('message.sent', (data) => {
  const exists = messages.value.some(msg => msg.MessageID === data.message.MessageID)
  if (!exists) {
    messages.value.push({
      ...data.message,
      sender: data.message.sender ?? { name: 'Unknown' },
    })
    scrollChatToBottom()
  }
})


  channel.bind('pusher:subscription_succeeded', () => {
    console.log('Subscribed to private-order channel successfully')
  })

  channel.bind('pusher:subscription_error', (status) => {
    console.error('Subscription error:', status)
  })
})

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
</script>
