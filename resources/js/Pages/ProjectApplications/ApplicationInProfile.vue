<template>
  <AuthenticatedLayout>
    <Head title="My Projects & Applications" />
    <div class="max-w-5xl mx-auto p-6 bg-white">
      <h2 class="text-2xl font-bold mb-4">All Applaction To My Projects</h2>

      <div v-if="projects.length === 0">
        <p>You have no projects.</p>
      </div>
      <div v-else class="space-y-6">
        <!-- Перебираем каждый проект -->
        <div v-for="proj in projects" :key="proj.id" class="border p-4 bg-gray-50 rounded">
          <h3 class="text-xl font-semibold">Project: {{ proj.title }}</h3>
          <p><strong>Budget:</strong> {{ proj.budget }}</p>

          <!-- Выводим заявки этого проекта -->
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
  </AuthenticatedLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const { props } = usePage()
const projects = props.projects  // <= массив проектов, внутри each project.applications


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
