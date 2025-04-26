<template>
  <AuthenticatedLayout>
    <Head title="Service Information" />

    <div class="my-6 sm:my-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 sm:p-8 space-y-10">

        <!-- Title -->
        <div class="text-center">
          <h1 class="text-2xl sm:text-3xl font-bold text-red-600 break-words whitespace-pre-wrap">{{ jobAds.Title }}</h1>
          <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-300">Posted {{ timeSincePosted }}</p>
        </div>

        <!-- Freelancer Info -->
        <div class="flex items-center space-x-4 mt-4 sm:mt-6">
          <a v-if="jobAds.creator?.avatar" :href="`/user/${jobAds.creator.username}`" class="flex-shrink-0">
            <img :src="jobAds.creator.avatar.photo_url" class="w-12 h-12 rounded-full border" alt="Freelancer Avatar" />
          </a>
          <a v-else :href="`/user/${jobAds.creator.username}`" class="flex-shrink-0">
            <div class="w-12 h-12 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center">
              {{ jobAds.creator.name.charAt(0).toUpperCase() }}
            </div>
          </a>
          <div>
            <a :href="`/user/${jobAds.creator.username}`" class="block text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-200 hover:text-blue-600">
              {{ jobAds.creator.name }}
            </a>
            <div v-if="jobAds.creator.role === 'freelancer'" class="text-xs text-blue-600 mt-1">Freelancer</div>
          </div>
        </div>

        <!-- Service Details -->
        <div class="space-y-6">
          <div class="bg-gray-100 dark:bg-gray-700 p-4 sm:p-6 rounded-lg">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Service Details</h2>
            <div class="mt-3 space-y-2">
              <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base"><strong>Price:</strong> ${{ jobAds.Price }}</p>
              <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base"><strong>Service Provider:</strong> {{ jobAds.creator.name }}</p>
            </div>
          </div>

          <div class="bg-gray-100 dark:bg-gray-700 p-4 sm:p-6 rounded-lg">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Service Description</h2>
            <p class="mt-3 text-gray-700 dark:text-gray-300 text-sm sm:text-base break-words whitespace-pre-wrap">
              {{ jobAds.Description }}
            </p>
          </div>

          <!-- Portfolio Section -->
          <div class="bg-gray-100 dark:bg-gray-700 p-4 sm:p-6 rounded-lg">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Examples of Work</h2>
            <div v-if="jobAds.portfolios.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
              <div
                v-for="example in jobAds.portfolios"
                :key="example.id"
                class="overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800 cursor-pointer"
                @click="openImage(example.example_url)"
              >
                <img :src="example.example_url" alt="Portfolio Example" class="w-full h-48 object-cover hover:scale-105 transition-transform" />
              </div>
            </div>
            <p v-else class="text-gray-500 mt-4">No examples provided yet.</p>
          </div>

          <!-- How to Request Section -->
          <div class="bg-red-50 border-l-4 border-red-400 p-4 sm:p-6 rounded-lg">
            <h2 class="text-xl sm:text-2xl font-semibold text-red-600">How to Request This Service</h2>
            <ul class="mt-3 space-y-2 text-gray-700 text-sm sm:text-base list-disc list-inside">
              <li>🚀 Send a clear and polite request explaining your needs.</li>
              <li>🚀 Specify expectations, deadlines, and any details.</li>
              <li>🚀 Feel free to ask the freelancer any questions before ordering.</li>
              <li>🚀 Click "Request Service" to contact and agree on terms.</li>
            </ul>
          </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-center mt-8">
          <button
            @click="navigateToApplicationForm"
            class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-base sm:text-lg rounded-full shadow-md transition"
          >
            🚀 Request Service
          </button>
        </div>

      </div>
    </div>

    <!-- Modal for Image Preview -->
    <div v-if="selectedImage" class="fixed inset-0 bg-black bg-opacity-80 flex justify-center items-center z-50">
      <div class="relative">
        <img :src="selectedImage" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg" />
        <button
          @click="closeImage"
          class="absolute top-2 right-2 text-white text-3xl font-bold hover:text-red-500"
        >
          X
        </button>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props: pageProps } = usePage();

const props = defineProps({
  jobAds: Object,
});

const selectedImage = ref(null);

const openImage = (url) => {
  selectedImage.value = url;
};

const closeImage = () => {
  selectedImage.value = null;
};

const timeSincePosted = computed(() => {
  const postedDate = new Date(props.jobAds.created_at);
  const now = new Date();
  const diff = Math.floor((now - postedDate) / (1000 * 60 * 60 * 24));
  if (diff === 0) return "Today";
  if (diff === 1) return "1 day ago";
  return `${diff} days ago`;
});

const navigateToApplicationForm = () => {
  router.visit(`/jobs/${props.jobAds.id}/apply`);
};
</script>
