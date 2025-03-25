<template>
  <AuthenticatedLayout>
    <Head title="Projects Management" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
      <h1 class="text-2xl font-bold mb-4">Projects</h1>

      <!-- Projects table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left">ID</th>
              <th class="px-6 py-3 text-left">Title</th>
              <th class="px-6 py-3 text-left">Description</th>
              <th class="px-6 py-3 text-left">Budget</th>
              <th class="px-6 py-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="project in projects.data" :key="project.id">
              <td class="px-6 py-4">{{ project.id }}</td>
              <td class="px-6 py-4 truncate max-w-[150px]">{{ project.title }}</td>
              <td class="px-6 py-4 truncate max-w-[300px]">{{ project.description }}</td>
              <td class="px-6 py-4">{{ project.budget }} $</td>
              <td class="px-6 py-4">
                <button
                  @click="deleteProject(project.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex items-center space-x-2">
        <button
          :disabled="!projects.prev_page_url"
          @click="goTo(projects.prev_page_url)"
          class="px-3 py-2 bg-gray-300 text-sm rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button
          :disabled="!projects.next_page_url"
          @click="goTo(projects.next_page_url)"
          class="px-3 py-2 bg-gray-300 text-sm rounded disabled:opacity-50"
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
