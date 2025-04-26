<template>
    <div class="mt-6">
      <!-- Average Rating -->
      <div v-if="reviews.length > 0" class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Average Rating</h3>
        <p class="text-red-600 text-3xl font-extrabold mt-1">{{ averageRating }}/5</p>
        <p class="text-sm text-gray-600 mt-1">
          Based on {{ reviews.length }} review<span v-if="reviews.length > 1">s</span>
        </p>
      </div>
  
  
      <!-- Reviews -->
      <div class="mt-6">
        <h3 class="text-xl font-semibold text-gray-900">Reviews</h3>
        <div v-if="reviews.length === 0" class="mt-2 text-sm text-gray-600">
          No reviews yet.
        </div>
        <ul class="mt-4 space-y-4">
          <li v-for="review in reviews" :key="review.ReviewID" class="border-b border-gray-200 pb-4">
            <div v-if="editForm.id === review.ReviewID">
              <div>
                <label for="editRating" class="block text-sm font-medium">Rating</label>
                <select id="editRating" v-model="editForm.Rating" class="mt-1 block w-full">
                  <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                </select>
              </div>
              <div class="mt-2">
                <label for="editReviewText" class="block text-sm font-medium">Review</label>
                <textarea id="editReviewText" v-model="editForm.ReviewText" class="mt-1 block w-full"></textarea>
                <InputError class="mt-2" :message="form.errors.ReviewText" />
              </div>
              <div class="mt-2 flex space-x-2">
                <PrimaryButton @click="updateReview" class="bg-blue-600 text-white">Update Review</PrimaryButton>
                <button @click="cancelEdit" class="text-red-600 hover:text-red-800">Cancel</button>
              </div>
            </div>
            <div v-else>
              <p class="text-gray-900">{{ review.ReviewText }}</p>
              <p class="text-sm text-gray-600">
                {{ review.user.name }} (Rating: {{ review.Rating }})
                <span v-if="authUser && review.user.id === authUser.id">
                  <div class="dropdown relative inline-block text-left">
                    <button tabindex="0" class="flex items-center p-1 rounded-full hover:bg-gray-200 transition">
                    </button>
                    <ul tabindex="0" class="dropdown-content absolute mt-2 w-40 menu bg-base-100 rounded-md shadow-lg p-1 z-10">
                      <li>
                        <button @click="editReview(review)" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 hover:text-blue-600 text-left">
                          Edit
                        </button>
                      </li>
                      <li>
                        <button @click="deleteReview(review)" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 hover:text-red-600 text-left">
                          Delete
                        </button>
                      </li>
                    </ul>
                  </div>
                </span>
              </p>
            </div>
          </li>
        </ul>
      </div>
  
      <!-- Add New Review -->
      <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-900">Add a Review</h3>
        <form @submit.prevent="submitReview" class="space-y-4 mt-4">
          <div>
            <label for="rating" class="block text-sm font-medium">Rating</label>
            <select id="rating" v-model="form.Rating" class="select select-bordered mt-1 block w-full">
              <option disabled selected value="">Choose rating</option>
              <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
            </select>
          </div>
          <div>
            <label for="ReviewText" class="block text-sm font-medium">Review</label>
            <textarea id="ReviewText" v-model="form.ReviewText" class="textarea textarea-bordered mt-1 block w-full"></textarea>
          </div>
          <PrimaryButton type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md">Post Review</PrimaryButton>
          <InputError class="mt-2" :message="form.errors.ReviewText" />
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, router } from '@inertiajs/vue3';
  import InputError from '@/Components/InputError.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  
  const props = defineProps({
    reviews: Array,
    averageRating: String,
    authUser: Object,
    jobAdId: Number,
  });
  
  const form = useForm({
    Rating: '',
    ReviewText: '',
  });
  
  const editForm = useForm({
    Rating: '',
    ReviewText: '',
    id: null,
  });
  
  const submitReview = () => {
    form.post(route('jobAds.addReview', props.jobAdId), {
      onSuccess: () => {
        form.reset();
        router.reload({ only: ['jobAds.reviews', 'averageRating'] });
      },
    });
  };
  
  const deleteReview = (review) => {
    if (confirm('Are you sure you want to delete this review?')) {
      form.delete(`/reviews/${review.ReviewID}`, {
        onSuccess: () => router.reload({ only: ['jobAds.reviews', 'averageRating'] }),
      });
    }
  };
  
  const editReview = (review) => {
    editForm.id = review.ReviewID;
    editForm.Rating = review.Rating;
    editForm.ReviewText = review.ReviewText;
  };
  
  const cancelEdit = () => {
    editForm.reset();
    editForm.id = null;
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