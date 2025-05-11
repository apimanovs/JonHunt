<template>
  <Head title="Profile page" />

  <AuthenticatedLayout>
    <div class="container mx-auto py-10 px-4">
      <div class="max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-red-600 via-red-500 to-red-700 h-52 relative">
          <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2">
            <img
              v-if="user.avatar?.photo_url"
              :src="user.avatar.photo_url"
              alt="Avatar"
              class="w-32 h-32 rounded-full border-4 border-white object-cover"
            />
            <div
              v-else
              class="w-32 h-32 rounded-full bg-gray-400 flex items-center justify-center text-white text-4xl font-bold border-4 border-white"
            >
              {{ user.name.charAt(0).toUpperCase() }}
            </div>
          </div>
        </div>

        <div class="mt-20 px-8 pb-8 text-center">
          <h2 class="text-4xl font-extrabold text-gray-800 break-words">{{ user.name }}</h2>
          <p class="text-gray-600 mt-2 break-words">{{ user.email }}</p>
          <p class="text-gray-400 text-sm mt-1 capitalize">Role: {{ user.role }}</p>
          <p v-if="user.description" class="mt-4 text-gray-600 italic break-words">{{ user.description }}</p>

          <template v-if="user.role === 'freelancer'">
            <div class="mt-10 text-left space-y-8">
              <h3 class="text-3xl font-bold text-gray-700 mb-6 border-b-2 border-gray-300 pb-2">🎯 Freelancer Details</h3>
              <div class="space-y-6 text-gray-700">
                <div>
                  <h4 class="text-2xl font-semibold mb-2 text-gray-800">Specialization</h4>
                  <p class="break-words">{{ freelancer.specialization || 'Not provided' }}</p>
                </div>
                <div>
                  <h4 class="text-2xl font-semibold mb-2 text-gray-800">Country</h4>
                  <p class="break-words">{{ freelancer.country || 'Not provided' }}</p>
                </div>
                <div>
                  <h4 class="text-2xl font-semibold mb-2 text-gray-800">Bio</h4>
                  <p class="break-words">{{ freelancer.bio || 'Not provided' }}</p>
                </div>
                <div>
                  <div>
                    <h4 class="text-2xl font-semibold mb-2 text-gray-800">Skills</h4>
                    
                    <div v-if="freelancer.skills?.length" class="flex flex-wrap gap-2 mt-2">
                      <span
                        v-for="skill in freelancer.skills"
                        :key="skill.id || skill"
                        class="inline-block bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full"
                      >
                        {{ skill.name || skill }}
                      </span>
                    </div>
                  
                    <p v-else class="text-gray-400">No skills available</p>
                  </div>                  
                </div>
                <div v-if="freelancer.portfolio" class="mt-4">
                  <a :href="freelancer.portfolio" target="_blank" class="text-blue-600 hover:underline text-lg">
                    🌐 View Portfolio
                  </a>
                </div>
              </div>
            </div>
          </template>
        </div>

        <div v-if="projects?.length" class="px-8 pb-12">
          <h3 class="text-3xl font-bold text-gray-700 mb-8">📚 Projects</h3>
          <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="project in projects" :key="project.id" class="bg-gray-50 rounded-lg p-6 shadow hover:shadow-lg transition border border-gray-200">
              <a :href="`/projects/${project.id}`" class="text-xl font-bold text-red-600 hover:text-red-700 block mb-2 break-words">
                {{ project.title }}
              </a>
              <p class="text-gray-600 mb-2 break-words">{{ project.description || 'No description provided.' }}</p>
              <div class="text-sm text-gray-500 space-y-1">
                <p><strong>Budget:</strong> ${{ project.budget || 'Not specified' }}</p>
                <p v-if="project.niche"><strong>Niche:</strong> {{ project.niche }}</p>
              </div>
            </div>
          </div>
        </div>
        

        <div v-if="jobAds?.length" class="px-8 pb-12">
          <h3 class="text-3xl font-bold text-gray-700 mb-8">💼 Freelancer Ads</h3>
          <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="ad in jobAds" :key="ad.id" class="bg-gray-50 rounded-lg p-6 shadow hover:shadow-lg transition border border-gray-200">
              <a :href="`/gigs/${ad.id}`" class="text-xl font-bold text-red-600 hover:text-red-700 mb-2 block break-words">
                {{ ad.title || 'No title provided.' }}
              </a>
              <p class="text-gray-600 mb-2 break-words">{{ ad.description || 'No description provided.' }}</p>
              <div class="text-sm text-gray-500 space-y-1">
                <p><strong>Price:</strong> ${{ ad.price || 'Not specified' }}</p>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!projects?.length && !jobAds?.length" class="text-center py-10 text-gray-500">
          This user has no projects or freelancer ads yet.
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  freelancer: Object,
  user: Object,
  projects: Array,
  jobAds: Array,
});

const { freelancer, user, projects, jobAds } = props;
</script>
