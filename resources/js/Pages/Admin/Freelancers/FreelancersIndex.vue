<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  pendingFreelancers: Array
});

console.log(props);
</script>

<template>
  <Head title="Freelancers Requests" />

  <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Pending Freelancers</h1>

    <div v-if="pendingFreelancers.length">
      <table class="table-auto w-full border">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">User</th>
            <th class="px-4 py-2">Country</th>
            <th class="px-4 py-2">Specialization</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="f in pendingFreelancers" :key="f.id" class="border-t">
            <td class="px-4 py-2">{{ f.id }}</td>
            <td class="px-4 py-2">{{ f.user_id.name }} (User ID: {{ f.user_id }})</td>
            <td class="px-4 py-2">{{ f.country }}</td>
            <td class="px-4 py-2">{{ f.specialization }}</td>
            <td class="px-4 py-2 space-x-2">
              <form :action="route('admin.freelancers.approve', { freelancer: f.id })" method="POST" class="inline">
                <input type="hidden" name="_token" :value="$page.props.csrf" />
                <button
                  type="submit"
                  class="px-2 py-1 bg-green-500 text-white rounded"
                >
                  Approve
                </button>
              </form>

              <form :action="route('admin.freelancers.reject', { freelancer: f.id })" method="POST" class="inline">
                <input type="hidden" name="_token" :value="$page.props.csrf" />
                <input type="hidden" name="_method" value="DELETE" />
                <button
                  type="submit"
                  class="px-2 py-1 bg-red-500 text-white rounded"
                >
                  Reject
                </button>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p>No pending freelancers found.</p>
    </div>
  </div>
</template>
