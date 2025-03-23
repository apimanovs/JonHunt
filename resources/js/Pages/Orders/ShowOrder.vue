<template>
  <AuthenticatedLayout>
    <Head title="Order Details" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="max-w-7xl mx-auto px-4 py-6">
      <!-- Контейнер с двумя колонками -->
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Карточка с информацией о заказе -->
        <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
          <h2 class="text-3xl font-bold mb-4 text-gray-800 dark:text-gray-100">
            Order #{{ order.id }}
          </h2>
    
          <div class="mb-6">
            <p class="text-lg text-gray-800 dark:text-gray-300">
              <a :href="`/gigs/${order.job_application.job_ad.id}`" class="ml-2 text-2xl text-gray-600 hover:text-red-600">
                {{ order.job_application.job_ad.Title }}
              </a>
            </p>            
            <p v-if="auth.user?.id === order.freelancer.id" class="text-lg text-gray-800 dark:text-gray-300">
              <strong>Client:</strong>
              <a :href="`/profile/${order.client.username}`" class="ml-2 text-gray-600 hover:text-red-600">
                {{ order.client.name }}
              </a>
            </p>
            <p v-if="auth.user?.id === order.client_id" class="text-lg text-gray-800 dark:text-gray-300">
              <strong>Freelancer:</strong>
              <a :href="`/profile/${order.freelancer.username}`" class="ml-2 text-gray-600 hover:text-red-600">
                {{ order.freelancer.name }}
              </a>
            </p>
            
          </div>
    
          <div class="mb-6">
            <h3 v-if="auth.user?.id === order.client_id" class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100">
              Status
            </h3>
            <div v-if="auth.user?.id === order.client_id" class="p-4 border rounded-lg">
              <template v-if="order.status === 'in_progress'">
                <p class="text-gray-700 dark:text-gray-300">
                  Freelancer <span class="font-semibold">{{ order.freelancer.name }}</span> is currently working on your project.
                </p>
              </template>
              <template v-else-if="order.status === 'submitted'">
                <p class="text-gray-700 dark:text-gray-300">
                  Freelancer <span class="font-semibold">{{ order.freelancer.name }}</span> has submitted the work. Please review below.
                </p>
              </template>
              <template v-else-if="order.status === 'completed'">
                <p class="text-green-600 font-semibold">
                  This order is completed.
                </p>
              </template>
              <template v-else-if="order.status === 'cancelled'">
                <p class="text-red-600 font-semibold">
                  This order was cancelled.
                </p>
                <p v-if="order.cancel_reason" class="text-gray-700 dark:text-gray-300 mt-1">
                  <strong>Reason:</strong> {{ order.cancel_reason }}
                </p>
              </template>
            </div>
          </div>
    
          <!-- Блок действий для Клиента или Фрилансера -->
          <div>
            <!-- Для клиента -->
            <div v-if="auth.user?.id === order.client_id">
              <div v-if="order.status === 'submitted'" class="mt-6">
                <h3 class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100">
                  Submitted Work
                </h3>
                <p class="mb-2 text-gray-800 dark:text-gray-300">
                  <strong>Description:</strong> {{ order.result_text }}
                </p>
                <div class="mb-4">
                  <a
                    v-if="order.result_file"
                    :href="order.result_file"
                    target="_blank"
                    class="text-blue-600 hover:underline font-medium"
                  >
                    Download Attached File
                  </a>
                </div>
                <form @submit.prevent="completeOrder">
                  <button
                    type="submit"
                    class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out"
                  >
                    Mark as Complete
                  </button>
                </form>
              </div>
            </div>
    
            <!-- Для фрилансера -->
            <div v-else-if="auth.user?.id === order.freelancer_id">
              <div v-if="order.status === 'in_progress'" class="mt-6">
                <h3 class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100">
                  Submit Your Work
                </h3>
                <form @submit.prevent="submitWork" class="space-y-4">
                  <textarea
                    v-model="resultText"
                    placeholder="Describe your work..."
                    class="w-full p-3 border rounded-lg text-gray-800 dark:text-gray-300"
                    required
                  ></textarea>
                  <input type="file" @change="handleFileUpload" class="block" />
                  <button
                    type="submit"
                    class="bg-green-500 text-white px-5 py-2 rounded-lg hover:bg-green-600 transition duration-200 ease-in-out"
                  >
                    Submit Work
                  </button>
                </form>
              </div>
              <div v-else-if="order.status === 'submitted'" class="mt-6">
                <h3 class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100">
                  Your Submitted Work
                </h3>
                <p class="mb-2 text-gray-800 dark:text-gray-300">
                  <strong>Description:</strong> {{ order.result_text }}
                </p>
                <div>
                  <a
                    v-if="order.result_file"
                    :href="order.result_file"
                    target="_blank"
                    class="text-blue-600 hover:underline font-medium"
                  >
                    Download Attached File
                  </a>
                </div>
              </div>
              <!-- Кнопка отмены заказа -->
              <div v-if="order.status !== 'completed' && order.status !== 'cancelled'" class="mt-6">
                <button
                  @click="showCancelForm = !showCancelForm"
                  class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 ease-in-out"
                >
                  Cancel Order
                </button>
                <div v-if="showCancelForm" class="mt-4">
                  <form @submit.prevent="cancelOrder" class="space-y-3">
                    <textarea
                      v-model="cancelReason"
                      placeholder="Reason for cancellation..."
                      class="w-full p-3 border rounded-lg text-gray-800 dark:text-gray-300"
                      rows="3"
                    ></textarea>
                    <button
                      type="submit"
                      class="w-full px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 ease-in-out"
                    >
                      Confirm Cancellation
                    </button>
                  </form>
                </div>
              </div>
            </div>
    
            <!-- Если не клиент и не фрилансер -->
            <div v-else>
              <p class="text-gray-700">You do not have permission to view this order.</p>
            </div>
          </div>
        </div>
          

        <!-- Чат-окно -->
        <div class="lg:w-1/3 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex flex-col">
          <h3 class="text-2xl font-bold mb-4">Chat</h3>
          <div
            ref="chatContainer"
            class="flex-1 max-h-[400px] overflow-y-auto border rounded-lg p-4 bg-gray-100"
            >
          <div
          v-for="msg in messages"
          :key="msg.MessageID"
          class="chat"
          :class="msg.SenderID === auth.user.id ? 'chat-end' : 'chat-start'"
        >
              <div class="flex items-start">
                <div>
                  <div class="flex items-center">
                    <span class="font-semibold mr-2">{{ msg.sender.name }}</span>
                    <time class="text-xs text-gray-500">
                      {{ new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                    </time>
                  </div>
                  <div
                    class="mt-1 p-3 rounded-lg"
                    :class="msg.SenderID === auth.user.id ? 'bg-blue-500 text-white self-end' : 'bg-gray-200 text-black'"
                  >
                    {{ msg.Content }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Поле ввода сообщения -->
          <div class="mt-4 flex">
            <input
              type="text"
              v-model="chatMessage"
              placeholder="Type your message..."
              class="flex-1 p-3 border rounded-lg"
              @keyup.enter="sendMessage"
            />
            <button
              @click="sendMessage"
              class="ml-2 px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
            >
              Send
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
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
