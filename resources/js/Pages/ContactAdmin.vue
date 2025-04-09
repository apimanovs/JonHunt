<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const message = ref('')
const error = ref(null)
const success = ref(null)

const submit = () => {
  error.value = null
  success.value = null

  router.post(route('admin-message.store'), { message: message.value }, {
    onError: (errors) => {
      error.value = errors.message || 'Something went wrong'
    },
    onSuccess: () => {
      success.value = 'Сообщение успешно отправлено!'
      message.value = ''
    }
  })
}
</script>

<template>
  <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-8">
    <h1 class="text-2xl font-bold mb-4">Связаться с администрацией</h1>

    <p class="mb-4 text-gray-600">Если у вас возникли вопросы, предложения или жалобы — напишите нам:</p>

    <textarea v-model="message" rows="6" class="w-full p-3 border rounded mb-4" placeholder="Ваше сообщение..."></textarea>

    <button @click="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
      Отправить
    </button>

    <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
    <p v-if="success" class="mt-4 text-green-600">{{ success }}</p>
  </div>
</template>
