<template>
  <Head title="Job Ad Information" />
  <AuthenticatedLayout>
    <!-- Детали объявления -->
     <div class="my-10 max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 sm:p-8 ">
    <div class="my-10 max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 sm:p-8">
      <h2 class="text-2xl font-semibold text-gray-900 overflow-hidden text-ellipsis">{{ jobAds.Title }}</h2>
      <div class="creator-info flex items-center mt-2">
        <a
          v-if="jobAds.creator && jobAds.creator.avatar"
          :href="`/user/${jobAds.creator.username}`"
          class="flex items-center"
        >
          <img
            :src="jobAds.creator.avatar.photo_url"
            alt="Avatar"
            class="w-10 h-10 rounded-full mr-3"
          />
        </a>
        <a
          v-else
          :href="`/user/${jobAds.creator.username}`"
          class="flex items-center"
        >
          <div
            class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3"
          >
            {{ jobAds.creator.name.charAt(0).toUpperCase() }}
          </div>
        </a>
        <a
          :href="`/user/${jobAds.creator.username}`"
          class="text-gray-700 font-medium hover:text-blue-500 transition flex items-center"
        >
          {{ jobAds.creator.name }}
          <span
            v-if="jobAds.creator.role === 'freelancer'"
            class="badge badge-accent ml-2"
          >
            Freelancer
          </span>
        </a>
      </div>

      <br />

      <div class="mt-2">
        <h3 class="text-xl font-semibold text-gray-900 overflow-hidden text-ellipsis whitespace-nowrap">
          Details
        </h3>
        <p><strong>Price:</strong> ${{ jobAds.Price }}</p>
        <p><strong>Posted:</strong> {{ timeSincePosted }}</p>
        <p><strong>Creator:</strong> {{ jobAds.creator.name }}</p>
      </div>
      <br />

      <p class="mt-2 text-lg text-gray-800 font-medium overflow-hidden text-ellipsis">
        {{ jobAds.Description }}
      </p>
      <div class="mt-6 flex justify-between items-center">
        <button 
        @click="navigateToApplicationForm" 
        class="btn bg-blue-600 text-white hover:bg-blue-700"
        >
        Apply for Job
      </button>
    </div>
  </div>
  
  <ReviewsSection 
  :reviews="jobAds.reviews"
  :averageRating="averageRating"
  :authUser="auth.user"
  :jobAdId="jobAds.id"
/>

  </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, router, usePage, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import ReviewsSection from '@/Components/ReviewsSection.vue';

const { props: pageProps } = usePage();
const { auth } = pageProps;

const props = defineProps({
  jobAds: Object,
  averageRating: {
    type: String,
    default: ''
  },
});

console.log(props.jobAds);

const timeSincePosted = computed(() => {
  const postedDate = new Date(props.jobAds.created_at);
  const currentDate = new Date();
  const diffTime = Math.abs(currentDate - postedDate);
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
  if (diffDays === 0) return "Today";
  else if (diffDays === 1) return "1 day ago";
  else return `${diffDays} days ago`;
});

const navigateToApplicationForm = () => {
  router.visit(`/jobs/${props.jobAds.id}/apply`);
};

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
