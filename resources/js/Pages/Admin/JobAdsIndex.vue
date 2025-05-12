<template>
  <AuthenticatedLayout>
    <Head title="Job Ads Management" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
      <h1 class="text-2xl font-bold mb-4">Job Advertisements</h1>

      <div class="overflow-x-auto">
        <table class="min-w-full text-left divide-y divide-gray-200 table-auto">
          <thead>
            <tr>
              <th class="px-6 py-3">ID</th>
              <th class="px-6 py-3">Title</th>
              <th class="px-6 py-3">Description</th>
              <th class="px-6 py-3">Price</th>
              <th class="px-6 py-3">Creator</th>
              <th class="px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="jobAd in jobAds.data" :key="jobAd.id">
              <td class="px-6 py-4">{{ jobAd.id }}</td>
              <td class="px-6 py-4 max-w-[150px]">
                <a :href="`/job-ads/${jobAd.id}`" class="text-red-600 hover:text-red-700 font-medium">
                  {{ jobAd.Title }}
                </a>
              </td>
              
              <td class="px-6 py-4 truncate max-w-[250px]">
                {{ jobAd.Description }}
              </td>
              
              <td class="px-6 py-4">{{ jobAd.Price }} $</td>
              
              <td class="px-6 py-4">
                <div class="text-gray-900 font-semibold">{{ jobAd.creator.name || 'Unknown' }}</div>
                <div class="text-gray-500 text-xs">@{{ jobAd.creator?.username }}</div>
              </td>
              
              <td class="px-6 py-4">
                <button
                  @click="deleteJobAd(jobAd.id)"
                  class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600"
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
          :disabled="!jobAds.prev_page_url"
          @click="goTo(jobAds.prev_page_url)"
          class="px-3 py-2 bg-gray-300 text-sm rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button
          :disabled="!jobAds.next_page_url"
          @click="goTo(jobAds.next_page_url)"
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
  jobAds: Object,
})

function deleteJobAd(id) {
  if (confirm('Are you sure you want to delete this job ad?')) {
    router.delete(route('admin.jobAds.destroy', id))
  }
}

function goTo(url) {
  if (url) router.visit(url)
}
</script>
