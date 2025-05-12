<template>
  <AuthenticatedLayout>
    <Head title="Pending Projects" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
      <h1 class="text-2xl font-bold mb-6">Pending Projects</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="project in pendingProjects.data" :key="project.id" class="border rounded-lg p-4 shadow-sm bg-white">
          <h2 class="text-xl font-bold text-red-600 mb-2">
            <a :href="`/projects/${project.id}`" class="hover:underline">
              {{ project.title }}
            </a>
          </h2>

          <p class="text-gray-800 mb-2"><strong>Description:</strong> {{ project.description }}</p>
          <p class="text-gray-800 mb-2"><strong>Requirements:</strong> {{ project.requirements }}</p>
          <p class="text-gray-800 mb-2"><strong>Expected Outcome:</strong> {{ project.expected_outcome }}</p>
          <p class="text-gray-800 mb-2"><strong>Tasks:</strong> {{ project.tasks }}</p>

          <div class="mt-4 text-sm text-gray-600">
            <p><strong>Budget:</strong> {{ project.budget }} {{ project.budget_type === 'hourly' ? '/hr' : '$' }}</p>
            <p><strong>Submitted by:</strong> {{ project.creator?.name || 'Unknown' }} (@{{ project.creator?.username }})</p>
            <p><strong>Created at:</strong> {{ new Date(project.created_at).toLocaleDateString() }}</p>
          </div>

          <div class="mt-4 space-x-2">
            <form :action="route('admin.projects.approve', project.id)" method="POST" class="inline">
              <input type="hidden" name="_token" :value="csrf" />
              <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approve</button>
            </form>
            <form :action="route('admin.projects.reject', project.id)" method="POST" class="inline">
              <input type="hidden" name="_token" :value="csrf" />
              <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reject</button>
            </form>
          </div>
        </div>
      </div>

      <div class="mt-6 flex justify-center space-x-2">
        <button
          @click="goTo(pendingProjects.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button
          :disabled="!pendingProjects.next_page_url"
          @click="goTo(pendingProjects.next_page_url)"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { computed } from 'vue'

const props = usePage().props
const pendingProjects = computed(() => props.pendingProjects || { data: [] })
const csrf = computed(() => props.csrf || '')

function goTo(url) {
  if (url) router.get(url)
}
</script>
