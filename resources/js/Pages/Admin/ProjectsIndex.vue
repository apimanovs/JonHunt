<template>
  <AuthenticatedLayout>
    <Head title="Projects Management" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
      <h1 class="text-2xl font-bold mb-6">Projects Overview</h1>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">ID</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">Title & Link</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">Description</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">Budget</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">Creator</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">Created</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="project in projects.data" :key="project.id">
              <td class="px-6 py-4">{{ project.id }}</td>

              <td class="px-6 py-4">
                <a :href="`/projects/${project.id}`" class="font-semibold text-red-600 hover:text-red-700">
                  {{ project.title }}
                </a>
              </td>

              <td class="px-6 py-4 max-w-[300px]">
                <p class="text-gray-800 line-clamp-4">{{ project.description }}</p>
              </td>

              <td class="px-6 py-4 text-gray-900 font-medium">
                {{ project.budget }} {{ project.budget_type === 'hourly' ? '/hr' : '$' }}
              </td>

              <td class="px-6 py-4">
                <div class="text-gray-900 font-semibold">{{ project.creator.name || 'Unknown' }}</div>
                <div class="text-gray-500 text-xs">@{{ project.creator?.username }}</div>
              </td>

              <td class="px-6 py-4 text-gray-500">
                {{ new Date(project.created_at).toLocaleDateString() }}
              </td>

              <td class="px-6 py-4">
                <button
                  @click="deleteProject(project.id)"
                  class="text-red-600 hover:text-red-800 text-sm"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex items-center space-x-2">
        <button
          :disabled="!projects.prev_page_url"
          @click="goTo(projects.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button
          :disabled="!projects.next_page_url"
          @click="goTo(projects.next_page_url)"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  projects: Object, // paginated projects
})

function deleteProject(id) {
  if (confirm('Are you sure you want to delete this project?')) {
    router.delete(route('admin.projects.destroy', id))
  }
}

function goTo(url) {
  if (url) router.visit(url, { preserveScroll: true, preserveState: true })
}
</script>
