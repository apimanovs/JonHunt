<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
defineProps({ messages: Array });
</script>

<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">User Messages</h1>

    <div
      v-for="msg in messages.data"
      :key="msg.id"
      class="border p-4 rounded mb-4 bg-white shadow"
    >
      <p><strong>User:</strong> {{ msg.user.name }} ({{ msg.user.email }})</p>
      <p class="mt-2"><strong>Message:</strong> {{ msg.message }}</p>

      <div v-if="msg.admin_reply" class="mt-2 text-green-600">
        <strong>Reply:</strong> {{ msg.admin_reply }}
      </div>

      <div v-else class="mt-4">
        <AdminReplyForm :id="msg.id" />
      </div>
    </div>
  </div>
</template>

<script>
import AdminReplyForm from '@/Components/AdminReplyForm.vue';
export default { components: { AdminReplyForm } };
</script>
