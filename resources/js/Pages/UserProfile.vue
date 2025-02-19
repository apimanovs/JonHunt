<template>
  <AuthenticatedLayout>
    <div class="container mx-auto my-10">
      <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Верхний раздел с градиентом -->
        <div class="bg-gradient-to-l from-red-600 to-purple-500 h-32 relative">
          <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 -mb-10">
            <img
              v-if="user.avatar && user.avatar.photo_url"
              :src="user.avatar.photo_url"
              alt="Avatar"
              class="w-20 h-20 rounded-full border-4 border-white"
            />
            <div
              v-else
              class="w-20 h-20 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold border-4 border-white"
            >
              {{ user.name.charAt(0).toUpperCase() }}
            </div>
          </div>
        </div>

        <div class="mt-12 px-6 pb-6 text-center">
          <h2 class="mt-4 text-2xl font-semibold text-gray-800 flex items-center justify-center">
            {{ user.name }}
            <span v-if="user.role === 'freelancer'" class="badge badge-accent ml-2">
              Freelancer
            </span>
          </h2>
          
          <p v-if="user.description" class="mt-4 text-gray-500">{{ user.description }}</p>
        </div>

        <div class="px-6 py-4">
          <h3 class="text-lg font-medium text-gray-700">User Information</h3>
          <p><strong>Email:</strong> {{ user.email }}</p>
          <p><strong>Role:</strong> {{ user.role }}</p>

          <template v-if="user.role === 'freelancer'">
            <h3 class="text-lg font-medium text-gray-700 mt-6">Freelancer Details</h3>
            <p><strong>Specialization:</strong> {{ freelancer.specialization }}</p>
            <p><strong>Country:</strong> {{ freelancer.country }}</p>
            <p><strong>Hourly Rate:</strong> ${{ freelancer.hourly_rate }}</p>
            <p><strong>Bio:</strong> {{ freelancer.bio }}</p>
            <p><strong>Skills:</strong></p>
            <ul v-if="freelancer.skills && freelancer.skills.length">
              <li v-for="skill in freelancer.skills" :key="skill.id || skill">
                {{ skill.name || skill }}
              </li>
            </ul>
            <p v-else>No skills available</p>
            <a
              v-if="freelancer.portfolio"
              :href="freelancer.portfolio"
              class="text-blue-500"
              target="_blank"
            >
              View Portfolio
            </a>
          </template>
        </div>

        <div v-if="projects && projects.length > 0" class="px-6 py-4">
          <h3 class="text-lg font-medium text-gray-700">Projects</h3>
          <ul class="mt-4 space-y-4">
            <li
              v-for="project in projects"
              :key="project.id"
              class="bg-gray-100 p-4 rounded-lg shadow-sm hover:shadow-md transition"
            >
              <h4 class="text-blue-600 font-semibold">{{ project.title }}</h4>
              <p class="text-gray-600 text-sm mt-2">{{ project.description }}</p>
            </li>
          </ul>
        </div>
        <div v-else class="text-gray-500 px-6 py-4">
          This user has not participated in any projects yet.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  freelancer: Object,
  user: Object,
  projects: Array,
  skills: Object,
  avatar: Object,
});

console.log(props.skills);
console.log(props.projects);
console.log(props.freelancer);
console.log(props.user);
console.log(props.avatar);

</script>

<style scoped>
</style>
