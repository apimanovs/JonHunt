<template>
  <AuthenticatedLayout>
    <Head title="Admin Dashboard" />

    <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
      <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

      <!-- Statistics block -->
      <div class="stats shadow mb-8">
        <div class="stat">
          <div class="stat-figure text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              class="inline-block h-8 w-8 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5V4H2v16h5m10-10l-4 4-4-4" />
            </svg>
          </div>
          <div class="stat-title">Total Users</div>
          <div class="stat-value">{{ totalUsers }}</div>
          <div class="stat-desc">Current platform users</div>
        </div>

        <div class="stat">
          <div class="stat-figure text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              class="inline-block h-8 w-8 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 17v-6h13v6M9 7h13v6M3 7h3v13H3z" />
            </svg>
          </div>
          <div class="stat-title">Total Projects</div>
          <div class="stat-value">{{ totalProjects }}</div>
          <div class="stat-desc">Projects on the platform</div>
        </div>

        <div class="stat">
          <div class="stat-figure text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              class="inline-block h-8 w-8 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h8m-8 6h16" />
            </svg>
          </div>
          <div class="stat-title">Job Ads</div>
          <div class="stat-value">{{ totalJobAds }}</div>
          <div class="stat-desc">Active job advertisements</div>
        </div>
      </div>

      <div class="mb-6 p-4 border-l-4 border-red-500 bg-red-50 text-red-900 rounded">
        <h2 class="text-xl font-semibold mb-2">Welcome to the Admin Dashboard</h2>
        <p class="text-sm">
          This panel allows administrators to manage users, projects, job ads, and incoming requests.
          Please ensure that all moderation actions align with the platform's policies and terms of use.
        </p>
        <ul class="list-disc ml-5 mt-2 text-sm">
          <li>Only verified admins should access this page.</li>
          <li>Data changes take effect immediately.</li>
          <li>Use caution when deleting or modifying user content.</li>
        </ul>
      </div>

      <!-- Admin panel links -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        <Link :href="route('admin.users.index')" class="block p-4 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 20h5V4H2v16h5m10-10l-4 4-4-4" />
            </svg>
            <h2 class="text-lg font-semibold">Users</h2>
          </div>
          <p class="text-sm text-white/90">Manage all registered users, view their activity and details.</p>
        </Link>
      
        <Link :href="route('admin.projects.index')" class="block p-4 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 17v-6h13v6M9 7h13v6M3 7h3v13H3z" />
            </svg>
            <h2 class="text-lg font-semibold">Projects</h2>
          </div>
          <p class="text-sm text-white/90">Browse and manage all current and archived projects.</p>
        </Link>
      
        <Link :href="route('admin-message.index')" class="block p-4 bg-gray-700 text-white rounded-lg shadow hover:bg-gray-800 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-lg font-semibold">User Messages</h2>
          </div>
          <p class="text-sm text-white/90">Read and reply to messages submitted by users.</p>
        </Link>

        <Link :href="route('admin.reports.index')" class="block p-4 bg-blue-700 text-white rounded-lg shadow hover:bg-gray-800 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-lg font-semibold">User Reports</h2>
          </div>
          <p class="text-sm text-white/90">Read and reply to reports submitted by users.</p>
        </Link>
        
        <Link :href="route('admin.jobAds.index')" class="block p-4 bg-orange-500 text-white rounded-lg shadow hover:bg-orange-600 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 6h16M4 12h8m-8 6h16" />
            </svg>
            <h2 class="text-lg font-semibold">Job Ads</h2>
          </div>
          <p class="text-sm text-white/90">View and moderate job advertisements posted by users.</p>
        </Link>
      
        <Link :href="route('admin.jobAds.moderation')" class="block p-4 bg-purple-500 text-white rounded-lg shadow hover:bg-purple-600 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-lg font-semibold">Job Ads Requests</h2>
          </div>
          <p class="text-sm text-white/90">Moderate incoming job advertisements requests and submissions.</p>
        </Link>


        <Link :href="route('admin.projects.moderation')" class="block p-4 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-lg font-semibold">Project Requests</h2>
          </div>
          <p class="text-sm text-white/90">Review and approve or reject submitted projects awaiting moderation.</p>
        </Link>        
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props passed from the controller
const props = defineProps({
  totalUsers: Number,
  totalProjects: Number,
  totalJobAds: Number,
})
</script>
