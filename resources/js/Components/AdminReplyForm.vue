<script setup>
import { useForm } from '@inertiajs/vue3';
const props = defineProps({ id: Number });

const form = useForm({
  admin_reply: '',
});

const submit = () => {
  form.post(route('admin-message.reply', props.id), {
    preserveScroll: true,
  });
};
</script>

<template>
  <form @submit.prevent="submit">
    <textarea
      v-model="form.admin_reply"
      class="w-full mt-2 p-2 border rounded"
      rows="3"
      placeholder="Write your reply to the user..."
    ></textarea>

    <div v-if="form.errors.admin_reply" class="text-red-500 mt-2">
      {{ form.errors.admin_reply }}
    </div>

    <button
      type="submit"
      class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
      Send Reply
    </button>
  </form>
</template>
