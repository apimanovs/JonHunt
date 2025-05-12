<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  reports: Object
});

console.log(props);
</script>

<template>
    <AuthenticatedLayout>

  <Head title="User Reports" />
  <div class="max-w-7xl mx-auto p-6 bg-white rounded shadow mt-10">
    <h1 class="text-2xl font-bold mb-4">User Reports</h1>

    <div v-if="props.reports.data.length">
        <table class="w-full table-auto border">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2">User</th>
              <th class="px-4 py-2">Reason</th>
              <th class="px-4 py-2">Project / JobAd</th>
              <th class="px-4 py-2">Project / JobAd Title</th>
              <th class="px-4 py-2">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="report in props.reports.data" :key="report.id" class="border-t">
              <td class="px-4 py-2">{{ report.user?.name }}</td>
              <td class="px-4 py-2">{{ report.reason }}</td>
              <td class="px-4 py-2">
                <div v-if="report.project">
                  <a :href="`/projects/${report.project.id}`" class="text-red-600 ">Project #{{ report.project.id }}</a>
                </div>
                <div v-else-if="report.job_advertisement">
                  <a :href="`/gigs/${report.job_advertisement.id}`" class="text-red-600 ">Job Ad #{{ report.job_advertisement.id }}</a>
                </div>
                <div v-else>
                  N/A
                </div>
              </td>
              <td class="px-4 py-2">
                <div v-if="report.project">
                  <a :href="`/projects/${report.project.id}`" class="text-red-600 ">{{ report.project.title }}</a>
                </div>
                <div v-else-if="report.job_advertisement">
                  <a :href="`/gigs/${report.job_advertisement.id}`" class="text-red-600 ">{{ report.job_advertisement.Title }}</a>
                </div>
                <div v-else>
                  N/A
                </div>
              </td>
              <td class="px-4 py-2">{{ new Date(report.created_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      
    <div v-else class="text-gray-500">
      No reports found.
    </div>
  </div>
</AuthenticatedLayout>

</template>
