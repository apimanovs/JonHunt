<template>
  <AuthenticatedLayout>
    <Head title="My Applications" />
    <div class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800">
      <h2 class="text-2xl font-bold mb-4">Applications</h2>

      <!-- Вкладки -->
      <div class="tabs flex gap-4 mb-6">
        <button
          :class="{'border-b-2 border-blue-500': activeTab === 'owner'}"
          @click="activeTab = 'owner'"
          class="px-4 py-2"
        >
          Applications to My Projects
        </button>
        <button
          :class="{'border-b-2 border-blue-500': activeTab === 'freelancer'}"
          @click="activeTab = 'freelancer'"
          class="px-4 py-2"
        >
          My Applications
        </button>
      </div>

      <!-- Вкладка: Владелец проектов -->
      <div v-if="activeTab === 'owner'">
        <div v-if="projects.length === 0">
          <p>You have no projects.</p>
        </div>
        <div v-else class="space-y-6">
          <div
            v-for="proj in projects"
            :key="proj.id"
            class="border p-4 bg-gray-50 dark:bg-gray-700 rounded"
          >
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Project: {{ proj.title }}</h3>
            <p class="text-gray-700 dark:text-gray-300"><strong>Budget:</strong> {{ proj.budget }}</p>

            <div v-if="proj.applications.length === 0" class="mt-2">
              <p class="text-gray-500">No applications yet.</p>
            </div>

            <div v-else class="mt-4 space-y-4">
              <div
                v-for="app in proj.applications"
                :key="app.id"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 p-4 rounded shadow-sm"
              >
                <!-- User Info -->
                <div class="flex items-center mb-3">
                  <a :href="`/user/${app.freelancer?.username}`">
                    <img
                      v-if="app.freelancer?.avatar"
                      :src="app.freelancer.avatar.photo_url"
                      alt="Avatar"
                      class="w-12 h-12 rounded-full mr-3 border"
                    />
                    <div
                      v-else
                      class="w-12 h-12 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3"
                    >
                      {{ app.freelancer?.name.charAt(0).toUpperCase() }}
                    </div>
                  </a>
                  <a :href="`/user/${app.freelancer?.username}`" class="text-lg font-medium text-gray-500 dark:text-blue-300 hover:text-gray-600">
                    {{ app.freelancer?.name }}
                  </a>
                </div>

                <!-- Cover Letter + Status -->
                <div class="text-gray-700 dark:text-gray-200">
                  <p class="mb-2 text-xl"><strong>Cover letter</strong></p>
                  <p class="bg-gray-100 dark:bg-gray-700 p-3 rounded whitespace-pre-line">{{ app.cover_letter }}</p>
                  <p class="mt-3">
                    <strong>Status: </strong>
                    <span
                      :class="{
                        'text-yellow-600': app.status === 'pending',
                        'text-green-600': app.status === 'approved',
                        'text-red-600': app.status === 'reject'
                      }"
                    >
                      {{ app.status }}
                    </span>
                  </p>
                </div>

                <!-- Actions -->
                <div class="mt-4 flex gap-2" v-if="app.status === 'pending'">
                  <button
                    @click="approveApplication(app.id)"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                  >
                    Approve
                  </button>
                  <button
                    @click="rejectApplication(app.id)"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                  >
                    Reject
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Вкладка: Фрилансер (мои заявки) -->
      <div v-if="activeTab === 'freelancer'">
        <div v-if="myApplications.length === 0">
          <p>You have not applied to any projects.</p>
        </div>
        <div v-else class="space-y-6">
          <div
            v-for="app in myApplications"
            :key="app.id"
            class="border p-4 bg-gray-50 dark:bg-gray-700 rounded"
          >
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
              Project: {{ app.project.title }}
            </h3>
            <p class="text-gray-700 text-xl dark:text-gray-300"><strong>Cover letter:</strong></p>
            <p class="bg-white dark:bg-gray-800 p-3 rounded whitespace-pre-line mt-1 text-gray-800 dark:text-gray-100">{{ app.cover_letter }}</p>
            <p class="mt-2 text-gray-700 dark:text-gray-300"><strong>Status:</strong> {{ app.status }}</p>
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
    await axios.post(route('projects.applications.approve', applicationId))
    alert('Application approved!')
    location.reload()
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