<template>
  <AuthenticatedLayout>
    <Head title="My Applications" />
    <div class="max-w-6xl mx-auto p-8 bg-white dark:bg-gray-900 shadow-md rounded-xl">
      <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">📋 My Applications</h2>

      <div class="flex gap-4 mb-8 border-b border-gray-300 dark:border-gray-700">
        <button
          :class="{'text-red-600 border-b-4 border-red-500 font-semibold': activeTab === 'owner'}"
          @click="activeTab = 'owner'"
          class="px-4 py-2 focus:outline-none"
        >
          📁 Applications to My Projects
        </button>
        <button
          :class="{'text-red-600 border-b-4 border-red-500 font-semibold': activeTab === 'freelancer'}"
          @click="activeTab = 'freelancer'"
          class="px-4 py-2 focus:outline-none"
        >
          🙋 My Applications
        </button>
      </div>

      <div v-if="activeTab === 'owner'">
        <div v-if="projects.length === 0">
          <p class="text-gray-600 dark:text-gray-300">You have no projects.</p>
        </div>
        <div v-else class="space-y-8">
          <div
            v-for="proj in projects"
            :key="proj.id"
            class="p-6 bg-gray-100 dark:bg-gray-800 rounded-xl shadow"
          >
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 break-words">💼 Project: {{ proj.title }}</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4 break-words"><strong>💰 Budget:</strong> {{ proj.budget }}</p>

            <div v-if="proj.applications.length === 0">
              <p class="text-gray-500 italic">No applications yet.</p>
            </div>

            <div v-else class="space-y-6">
              <div
                v-for="app in proj.applications"
                :key="app.id"
                class="bg-white dark:bg-gray-900 p-5 rounded-xl shadow-md border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-4 mb-4">
                  <a :href="`/user/${app.freelancer?.username}`">
                    <img
                      v-if="app.freelancer?.avatar"
                      :src="app.freelancer.avatar.photo_url"
                      alt="Avatar"
                      class="w-12 h-12 rounded-full border shadow"
                    />
                    <div
                      v-else
                      class="w-12 h-12 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center"
                    >
                      {{ app.freelancer?.name.charAt(0).toUpperCase() }}
                    </div>
                  </a>
                  <a :href="`/user/${app.freelancer?.username}`" class="text-lg font-medium text-gray-700 dark:text-blue-300 hover:underline">
                    {{ app.freelancer?.name }}
                  </a>
                </div>

                <div class="text-gray-800 dark:text-gray-200">
                  <p class="font-semibold mb-2">📄 Cover Letter</p>
                  <p class="bg-gray-100 dark:bg-gray-800 p-4 rounded whitespace-pre-line">{{ app.cover_letter }}</p>
                  <p class="mt-4">
                    <strong>Status:</strong>
                    <span
                      :class="{
                        'text-yellow-500': app.status === 'pending',
                        'text-green-500': app.status === 'approved',
                        'text-red-500': app.status === 'reject'
                      }"
                      class="font-semibold"
                    >
                      {{ app.status }}
                    </span>
                  </p>
                </div>

                <div class="mt-4 flex gap-3" v-if="app.status === 'pending'">
                  <button
                    @click="approveApplication(app.id)"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow"
                  >
                    ✅ Approve
                  </button>
                  <button
                    @click="rejectApplication(app.id)"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow"
                  >
                    ❌ Reject
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="activeTab === 'freelancer'">
        <div v-if="myApplications.length === 0">
          <p class="text-gray-600 dark:text-gray-300">You have not applied to any projects.</p>
        </div>
        <div v-else class="space-y-8">
          <div
            v-for="app in myApplications"
            :key="app.id"
            class="p-6 bg-gray-100 dark:bg-gray-800 rounded-xl shadow"
          >
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 break-words">
              💼 Project: {{ app.project.title }}
            </h3>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1 break-words">📄 Cover Letter:</p>
            <p class="bg-white dark:bg-gray-900 p-4 rounded whitespace-pre-line text-gray-800 dark:text-gray-10 break-words">
              {{ app.cover_letter }}
            </p>
            <p class="mt-3 text-gray-600 dark:text-gray-300">
              <strong>Status:</strong>
              <span
                :class="{
                  'text-yellow-500': app.status === 'pending',
                  'text-green-500': app.status === 'approved',
                  'text-red-500': app.status === 'reject'
                }"
                class="font-semibold"
              >
                {{ app.status }}
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const { props } = usePage()
const projects = props.projects || []
const myApplications = props.myApplications || []

const activeTab = ref('owner')

const approveApplication = async (applicationId) => {
  try {
    const response = await axios.post(route('projects.applications.approve', applicationId))
    const redirectUrl = response.data.redirect_url
    window.location.href = redirectUrl
  } catch (error) {
    console.error(error)
    alert('Failed to approve.')
  }
}


const rejectApplication = async (applicationId) => {
  try {
    await axios.post(route('projects.applications.reject', applicationId))
    alert('Application rejected!')
    location.reload() 
  } catch (error) {
    console.error(error)
    alert('Failed to reject.')
  }
}
</script>
