<template>
  <AuthenticatedLayout>
    <Head title="Service Information" />

    <div class="my-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
      <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 sm:p-10 space-y-10">

      <!-- Title -->
      <section class="text-center">
        <h1 class="text-3xl font-bold text-red-600 break-words whitespace-pre-wrap">{{ jobAds.Title }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Posted {{ timeSincePosted }}</p>
      </section>

      <!-- Freelancer Info -->
      <section class="flex items-center space-x-4">
        <a v-if="jobAds.creator?.avatar" :href="`/user/${jobAds.creator.username}`">
          <img :src="jobAds.creator.avatar.photo_url" class="w-12 h-12 rounded-full border" />
        </a>
        <a v-else :href="`/user/${jobAds.creator.username}`">
          <div class="w-12 h-12 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center">
            {{ jobAds.creator.name.charAt(0).toUpperCase() }}
          </div>
        </a>
        <div>
          <a :href="`/user/${jobAds.creator.username}`" class="text-lg font-semibold text-gray-800 dark:text-gray-200 hover:text-blue-600">
            {{ jobAds.creator.name }}
          </a>
          <div v-if="jobAds.creator.role === 'freelancer'" class="text-xs text-red-600">Freelancer</div>
        </div>
      </section>

      <!-- Service Details -->
      <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">📋 Service Details</h2>
        <p><strong>Price:</strong> ${{ jobAds.Price }}</p>
      </section>

      <!-- Description -->
      <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">📝 Description</h2>
        <p class="text-gray-800 dark:text-gray-100 whitespace-pre-wrap break-words">{{ jobAds.Description }}</p>
      </section>

      <!-- Portfolio -->
      <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">🎨 Portfolio</h2>
        <div v-if="jobAds.portfolios.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div v-for="example in jobAds.portfolios" :key="example.id" class="cursor-pointer" @click="openImage(example.example_url)">
            <img :src="example.example_url" class="w-full h-48 object-cover rounded-lg hover:scale-105 transition-transform" />
          </div>
        </div>
        <p v-else class="text-gray-500">No examples provided yet.</p>
      </section>

      <!-- Average Rating -->
      <section v-if="reviews.length" class="text-center">
        <h3 class="text-2xl font-bold">⭐ Average Rating</h3>
        <p class="text-red-600 text-3xl font-extrabold">{{ averageRating }}/5</p>
        <p class="text-sm text-gray-600">Based on {{ reviews.length }} review<span v-if="reviews.length > 1">s</span></p>
      </section>

      <section class="bg-red-50 border-l-4 border-red-400 p-6 rounded-lg">
        <h2 class="text-xl font-semibold text-red-600 mb-2">🚀 How to Request This Service</h2>
        <ul class="list-disc pl-5 text-sm text-gray-800 space-y-1">
          <li>Send a clear and polite request explaining your needs.</li>
          <li>Specify expectations, deadlines, and any details.</li>
          <li>Feel free to ask the freelancer any questions before ordering.</li>
          <li>Click "Request Service" to contact and agree on terms.</li>
        </ul>
      </section>

      <!-- Button -->
      <div class="text-center">
        <button @click="navigateToApplicationForm" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-lg rounded-full shadow-md">
          🚀 Request Service
        </button>
      </div>
      
      <!-- Reviews -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900">Reviews</h3>
        <div v-if="jobAds.reviews.length === 0" class="mt-2 text-sm text-gray-600">No reviews yet.</div>
        <ul class="mt-4 space-y-4">
          <li v-for="review in jobAds.reviews" :key="review.ReviewID" class="border-b border-gray-200 pb-4">
            <div v-if="editForm.id === review.ReviewID">
              <div>
                <label for="editRating" class="block text-sm font-medium">Rating</label>
                <select id="editRating" v-model="editForm.Rating" class="mt-1 block w-full">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
              <div>
                <label for="editReviewText" class="block text-sm font-medium">Review</label>
                <textarea id="editReviewText" v-model="editForm.ReviewText" class="mt-1 block w-full"></textarea>
                <InputError class="mt-2" :message="form.errors.ReviewText" />
              </div>
              <div class="mt-2">
                <PrimaryButton @click="updateReview" class="bg-blue-600 text-white">Update Review</PrimaryButton>
                <button @click="cancelEdit" class="text-red-600 hover:text-red-800 ml-2">Cancel</button>
              </div>
            </div>
            <div v-else>
              <p class="text-gray-900">{{ review.ReviewText }}</p>
              <p class="text-sm text-gray-600">
                {{ review.user.name }} (Rating: {{ review.Rating }})
                <span v-if="auth.user && review.user.id === auth.user.id">
                  <div class="dropdown relative inline-block text-left">
                    <button tabindex="0" class="flex items-center p-1 rounded-full hover:bg-gray-200 transition">
                      . . .
                    </button>
                    <ul tabindex="0" class="dropdown-content absolute mt-2 w-40 menu bg-base-100 rounded-md shadow-lg p-1 z-10">
                      <li>
                        <button @click="editReview(review)" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 hover:text-blue-600 text-left">Edit</button>
                      </li>
                      <li>
                        <button @click="deleteReview(review)" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 hover:text-red-600 text-left">Delete</button>
                      </li>
                    </ul>
                  </div>
                </span>
              </p>
            </div>
          </li>
        </ul>

        <!-- Add Review Form (не трогаем) -->
        <div class="mt-6">
          <h3 class="text-xl font-semibold text-gray-900">Add a Review</h3>
          <form @submit.prevent="submitReview" class="space-y-4">
            <div>
              <label for="rating" class="block text-sm font-medium">Rating</label>
              <select id="rating" v-model="form.Rating" class="select select-bordered mt-1 block w-full">
                <option disabled selected>Choose rating for this job ad</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
            <div>
              <label for="ReviewText" class="block text-sm font-medium">Review</label>
              <textarea id="ReviewText" v-model="form.ReviewText" class="textarea textarea-bordered mt-1 block w-full"></textarea>
            </div>
            <div v-if="$page.props.errors.error" class="text-red-600 mb-4">{{ $page.props.errors.error }}</div>
            <div v-if="$page.props.errors.Rating" class="text-red-600 mb-4">{{ $page.props.errors.Rating }}</div>
            <PrimaryButton type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md">Post a review</PrimaryButton>
            <InputError class="mt-2" :message="$page.props.errors?.error" />
          </form>
        </div>
      </section>

      <!-- Instructions -->

      <!-- Modal -->
      <div v-if="selectedImage" class="fixed inset-0 bg-black bg-opacity-80 flex justify-center items-center z-50">
        <div class="relative">
          <img :src="selectedImage" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg" />
          <button @click="closeImage" class="absolute top-2 right-2 text-white text-3xl font-bold hover:text-red-500">X</button>
        </div>
      </div>
    </div>
    </div>
  </AuthenticatedLayout>
</template>


<script setup>
import { ref, computed } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const { props: pageProps } = usePage();
const { auth } = pageProps;

const reviews = pageProps.reviews ?? [];
const averageRating = pageProps.averageRating ?? '0.0';
const authUser = pageProps.auth?.user ?? null;

const form = useForm({
  Rating: '',
  ReviewText: '',
});



const props = defineProps({
  jobAds: Object,
  averageRating: String,
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

const editForm = useForm({
  Rating: '',
  ReviewText: '',
  id: null,
});

const submitReview = () => {
  form.post(route('jobAds.addReview', props.jobAds.id), {
    onSuccess: () => {
      form.reset();
      router.reload({ only: ['jobAds.reviews', 'averageRating'] });
    },
  });
};

const deleteReview = (review) => {
  if (confirm('Are you sure you want to delete this review?')) {
    form.delete(`/reviews/${review.ReviewID}`, {
      onSuccess: () => {
        router.reload({ only: ['jobAds.reviews', 'averageRating'] });
      },
      onError: (errors) => {
        console.log('Error deleting review:', errors);
      },
    });
  }
};

const cancelEdit = () => {
  editForm.reset();
  editForm.id = null;
};

const editReview = (review) => {
  editForm.id = review.ReviewID;
  editForm.Rating = review.Rating;
  editForm.ReviewText = review.ReviewText;
};

const updateReview = () => {
  editForm.post(`/reviews/${editForm.id}/edit`, {
    onSuccess: () => {
      editForm.reset();
      editForm.id = null;
      router.reload({ only: ['jobAds.reviews', 'averageRating'] });
    },
  });
};
</script>
