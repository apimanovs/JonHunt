<template>
  <AuthenticatedLayout>
    <Head title="My Projects & Applications" />
    <div class="max-w-5xl mx-auto p-6 bg-white">
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
            class="border p-4 bg-gray-50 rounded"
          >
            <h3 class="text-xl font-semibold">Project: {{ proj.title }}</h3>
            <p><strong>Budget:</strong> {{ proj.budget }}</p>
            <div v-if="proj.applications.length === 0" class="mt-2">
              <p class="text-gray-500">No applications yet.</p>
            </div>
            <div v-else class="mt-2 space-y-2">
              <div
                v-for="app in proj.applications"
                :key="app.id"
                class="border border-gray-200 p-2 rounded bg-white"
              >
                <p><strong>Freelancer:</strong> {{ app.freelancer?.name }}</p>
                <p><strong>Cover letter:</strong> {{ app.cover_letter }}</p>
                <p><strong>Status:</strong> {{ app.status }}</p>
                <div class="mt-4">
                  <button
                    v-if="app.status === 'pending'"
                    @click="approveApplication(app.id)"
                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
                  >
                    Approve
                  </button>
                  <button
                    v-if="app.status === 'pending'"
                    @click="rejectApplication(app.id)"
                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2"
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
            class="border p-4 bg-gray-50 rounded"
          >
            <h3 class="text-xl font-semibold">
              Project: {{ app.project.title }}
            </h3>
            <p><strong>Cover letter:</strong> {{ app.cover_letter }}</p>
            <p><strong>Status:</strong> {{ app.status }}</p>
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
// Массив проектов с заявками (для владельца проектов)
const projects = props.projects || []
// Массив заявок, отправленных текущим пользователем (для фрилансера)
const myApplications = props.myApplications || []

// Активная вкладка: 'owner' или 'freelancer'
const activeTab = ref('owner')

// Функции для одобрения/отклонения заявок
const approveApplication = async (applicationId) => {
  try {
    await axios.post(route('projects.applications.approve', applicationId))
    alert('Application approved!')
    location.reload() // или Inertia.reload()
  } catch (error) {
    console.error(error)
    alert('Failed to approve.')
  }
}

const rejectApplication = async (applicationId) => {
  try {
    await axios.post(route('projects.applications.reject', applicationId))
    alert('Application rejected!')
    location.reload() // или Inertia.reload()
  } catch (error) {
    console.error(error)
    alert('Failed to reject.')
  }
}
</script>
