<template>
  <AuthenticatedLayout>
    <Head title="Job Ads Moderation" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg mt-10">
      <h1 class="text-2xl font-bold mb-6">Pending Job Advertisements</h1>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">ID</th>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">Title</th>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">Description</th>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">Price</th>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">Posted By</th>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">Created</th>
              <th class="px-6 py-3 font-semibold text-gray-600 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="jobAd in pendingJobAds.data" :key="jobAd.id">
              <td class="px-6 py-4">{{ jobAd.id }}</td>

              <td class="px-6 py-4">
                <a :href="`/job-ads/${jobAd.id}`" class="font-semibold text-red-600 hover:text-red-700">
                  {{ jobAd.Title }}
                </a>
              </td>

              <td class="px-6 py-4 max-w-[300px]">
                <p class="text-gray-800 l">{{ jobAd.Description }}</p>
              </td>

              <td class="px-6 py-4 text-gray-900 font-medium">{{ jobAd.Price }} $</td>

              <td class="px-6 py-4">
                <div class="text-gray-900 font-semibold">
                  {{ jobAd.creator?.name || 'Unknown' }}
                </div>
                <div class="text-gray-500 text-xs">@{{ jobAd.creator?.username }}</div>
              </td>

              <td class="px-6 py-4 text-gray-500">
                {{ new Date(jobAd.created_at).toLocaleDateString() }}
              </td>

              <td class="px-6 py-4 space-x-2">
                <form :action="route('admin.jobAds.approve', jobAd.id)" method="POST" class="inline">
                  <input type="hidden" name="_token" :value="csrf" />
                  <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    Approve
                  </button>
                </form>

                <form :action="route('admin.jobAds.reject', jobAd.id)" method="POST" class="inline">
                  <input type="hidden" name="_token" :value="csrf" />
                  <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 mt-2">
                    Reject
                  </button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-6 flex items-center space-x-2">
        <button
          :disabled="!pendingJobAds.prev_page_url"
          @click="goTo(pendingJobAds.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button
          :disabled="!pendingJobAds.next_page_url"
          @click="goTo(pendingJobAds.next_page_url)"
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
const pendingJobAds = computed(() => props.pendingJobAds || [])
const csrf = computed(() => props.csrf || '')

function goTo(url) {
  if (url) router.visit(url, { preserveScroll: true, preserveState: true });
}
</script>
