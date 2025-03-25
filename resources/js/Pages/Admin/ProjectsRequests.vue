<template>
  <AuthenticatedLayout>
    <Head title="Project Moderation" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
      <h1 class="text-2xl font-bold mb-4">Pending Project Approvals</h1>

      <table class="min-w-full text-left divide-y divide-gray-200 table-auto">
        <thead>
          <tr>
            <th class="px-4 py-2">Title</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Budget</th>
            <th class="px-4 py-2">Submitted By</th>
            <th class="px-4 py-2">Created At</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in pendingProjects" :key="project.id" class="border-t">
            <td class="px-4 py-2 font-semibold">{{ project.title }}</td>
            <td class="px-4 py-2 max-w-xs truncate">{{ project.description }}</td>
            <td class="px-4 py-2">{{ project.budget }} $</td>
            <td class="px-4 py-2">{{ project.creator ?? 'Unknown' }}</td>
            <td class="px-4 py-2">{{ new Date(project.created_at).toLocaleString() }}</td>
            <td class="px-4 py-2 space-x-2">
              <form :action="route('admin.projects.approve', project.id)" method="POST" class="inline">
                <input type="hidden" name="_token" :value="csrf" />
                <button type="submit" class="btn bg-green-500 text-white px-3 py-1 rounded">Approve</button>
              </form>
              <form :action="route('admin.projects.reject', project.id)" method="POST" class="inline">
                <input type="hidden" name="_token" :value="csrf" />
                <button type="submit" class="btn bg-red-500 text-white px-3 py-1 rounded">Reject</button>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { computed } from 'vue'

const props = usePage().props
const pendingProjects = computed(() => props.pendingProjects || [])
const csrf = computed(() => props.csrf || '')
</script>
