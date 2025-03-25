<template>
    <AuthenticatedLayout>
      <Head title="Job Ads Moderation" />
  
      <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Pending Job Ads</h1>
  
        <table class="min-w-full text-left divide-y divide-gray-200 table-auto">
          <thead>
            <tr>
              <th class="px-4 py-2">Title</th>
              <th class="px-4 py-2">Description</th>
              <th class="px-4 py-2">Price</th>
              <th class="px-4 py-2">Posted By</th>
              <th class="px-4 py-2">Created At</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="jobAd in pendingJobAds" :key="jobAd.id" class="border-t">
              <td class="px-4 py-2 font-semibold">{{ jobAd.Title }}</td>
              <td class="px-4 py-2 max-w-xs truncate">{{ jobAd.Description }}</td>
              <td class="px-4 py-2">{{ jobAd.Price }} $</td>
              <td class="px-4 py-2">{{ jobAd.creator ?? 'Unknown' }}</td>
              <td class="px-4 py-2">{{ new Date(jobAd.created_at).toLocaleString() }}</td>
              <td class="px-4 py-2 space-x-2">
                <form :action="route('admin.jobAds.approve', jobAd.id)" method="POST" class="inline">
                  <input type="hidden" name="_token" :value="csrf" />
                  <button type="submit" class="btn bg-green-500 text-white px-3 py-1 rounded">Approve</button>
                </form>
                <form :action="route('admin.jobAds.reject', jobAd.id)" method="POST" class="inline">
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
  const pendingJobAds = computed(() => props.pendingJobAds || [])
  const csrf = computed(() => props.csrf || '')
  </script>
  