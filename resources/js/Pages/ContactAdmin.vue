<script setup>
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CharactedLimitedInput from '@/Components/CharactedLimitedInput.vue';

const message = ref('')
const error = ref(null)
const success = ref(null)

const submit = () => {
  error.value = null
  success.value = null

  router.post(route('admin-message.store'), { message: message.value }, {
    onError: (errors) => {
      error.value = errors.message || 'Something went wrong. Please try again later.'
    },
    onSuccess: () => {
      success.value = 'Your message has been successfully sent!'
      message.value = ''
    }
  })
}
</script>

<template>
  <Head title="Contact Support" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800 leading-tight">Contact Support</h2>
    </template>

    <div class="max-w-3xl mx-auto mt-10 p-8 bg-white shadow-md rounded-lg">
      <h1 class="text-3xl font-bold mb-4 text-gray-900">Need help or want to share feedback?</h1>
      <p class="text-gray-600 mb-6">
        If you have questions, ideas, or would like to report a problem, feel free to write to us.
        Our team will get back to you as soon as possible.
      </p>

      <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
      <CharactedLimitedInput
      id="message"
      v-model="message"
      label="Your Message"
      :max="2000"
      :textarea="true"
    />
    

      <div class="mt-4 flex justify-end">
        <button
          @click="submit"
          class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition font-semibold"
        >
          Send Message
        </button>
      </div>

      <p v-if="error" class="mt-4 text-red-600 font-medium">{{ error }}</p>
      <p v-if="success" class="mt-4 text-green-600 font-medium">{{ success }}</p>
    </div>
  </AuthenticatedLayout>
</template>
