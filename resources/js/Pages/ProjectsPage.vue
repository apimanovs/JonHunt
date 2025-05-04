<template>
  <Head title="Projects Information" />
  <AuthenticatedLayout>
    <div class="my-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
      <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 sm:p-10 space-y-10">


      <!-- Project Header -->
      <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
        <div class="flex items-center space-x-4">
          <a v-if="project.creator?.avatar" :href="`/user/${project.creator.username}`">
            <img :src="project.creator.avatar.photo_url" class="w-10 h-10 rounded-full border" />
          </a>
          <a v-else :href="`/user/${project.creator.username}`">
            <div class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center">
              {{ project.creator.name.charAt(0).toUpperCase() }}
            </div>
          </a>
          <a :href="`/user/${project.creator.username}`" class="text-gray-800 dark:text-gray-200 font-medium hover:text-red-600">
            {{ project.creator.name }}
            <span v-if="project.creator.role === 'freelancer'" class="ml-2 px-2 py-0.5 bg-red-200 text-red-800 text-xs rounded-full">Freelancer</span>
          </a>
        </div>
        <h2 class="text-3xl font-bold text-red-600 dark:text-white break-words">{{ project.title }}</h2>
      </section>

      <!-- Project Summary -->
      <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow text-center">
          <div class="text-sm text-gray-500">Total Budget</div>
          <div class="text-2xl text-primary font-bold break-words">${{ project.budget }}</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow text-center">
          <div class="text-sm text-gray-500">Deadline</div>
          <div class="text-2xl text-secondary font-bold break-words">{{ deadline }}</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow text-center">
          <div class="text-sm text-gray-500">Niche</div>
          <div class="text-2xl font-bold break-words">{{ project.niche }}</div>
        </div>
      </section>

      <!-- Description -->
      <section class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">📝 Description</h3>
        <p class="text-gray-800 dark:text-gray-100 text-lg break-words">{{ project.description }}</p>
      </section>

      <!-- Expected Outcome / Requirements / Tasks -->
      <section class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
        <div class="border-l-4 border-red-500 bg-blue-50 dark:bg-red-900 p-5 rounded shadow">
          <h4 class="text-blue-700 dark:text-blue-200 text-xl font-semibold">🎯 Expected Outcome</h4>
          <p class="text-gray-800 dark:text-gray-100 mt-1 break-words whitespace-pre-wrap overflow-x-auto">{{ project.expected_outcome }}</p>
        </div>
        <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900 p-5 rounded shadow">
          <h4 class="text-green-700 dark:text-green-200 text-xl font-semibold">📌 Requirements</h4>
          <p class="text-gray-800 dark:text-gray-100 mt-1 break-words overflow-x-auto">{{ project.requirements }}</p>
        </div>
        <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900 p-5 rounded shadow">
          <h4 class="text-yellow-700 dark:text-yellow-200 text-xl font-semibold">✅ Tasks</h4>
          <p class="text-gray-800 dark:text-gray-100 mt-1 whitespace-pre-wrap break-all overflow-x-auto">
            {{ project.tasks }}
          </p>        
        </div>
      </section>

      <!-- Current User Application -->
      <section v-if="currentUserApplication" class="bg-gray-50 dark:bg-gray-700 p-6 rounded border dark:border-gray-600">
        <h3 class="text-xl font-semibold mb-2">Your Application</h3>
        <p><strong>Status:</strong> {{ currentUserApplication.status }}</p>
        <p class="mt-1"><strong>Cover Letter:</strong> {{ currentUserApplication.cover_letter }}</p>
      </section>

      <!-- Reviews -->
      <section>
        <div v-if="Array.isArray(reviews) && reviews.length > 0" class="text-center mb-6">
          <h3 class="text-2xl font-bold text-gray-900">⭐ Average Rating</h3>
          <p class="text-red-600 text-3xl font-extrabold mt-1">{{ averageRating }}/5</p>
          <p class="text-sm text-gray-600 mt-1">
            Based on {{ reviews.length }} review<span v-if="reviews.length > 1">s</span>
          </p>
        </div>

        <div class="mt-6">
          <h3 class="text-xl font-semibold text-gray-900">Reviews</h3>
          <div v-if="project.reviews.length === 0" class="mt-2 text-sm text-gray-600">No reviews yet.</div>
          <ul class="mt-4 space-y-4">
            <li v-for="review in project.reviews" :key="review.ReviewID" class="border-b border-gray-200 pb-4">
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
                         ...
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
        </div>

        <!-- Add Review Form -->
        <div class="mt-6">
          <h3 class="text-xl font-semibold text-gray-900">Add a Review</h3>
          <form @submit.prevent="submitReview" class="space-y-4">
            <div>
              <label for="rating" class="block text-sm font-medium">Rating</label>
              <select id="rating" v-model="form.Rating" class="select select-bordered mt-1 block w-full">
                <option disabled selected>Choose rating for this project</option>
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
          </form>
        </div>
      </section>

      <!-- Sidebar: Application Form -->
      <div class="lg:col-span-1">
        <div v-if="canApply" class="bg-blue-50 dark:bg-gray-900 p-6 rounded shadow sticky top-28">
          <h3 class="text-xl font-semibold mb-4 text-red-700 dark:text-blue-300">Apply to this Project</h3>
          <form @submit.prevent="submitApplication" class="space-y-4">
            <div>
              <label class="block font-semibold">Cover Letter</label>
              <textarea v-model="coverLetter" class="border w-full p-2 rounded" rows="4" placeholder="Explain why you are the best fit..." required></textarea>
            </div>
            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 w-full">Submit Application</button>
          </form>
        </div>
      </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

  
  <script setup>
import { computed, ref } from 'vue';
import AuthenticatedLayout from './../Layouts/AuthenticatedLayout.vue';
import { useForm, Head, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { Inertia } from '@inertiajs/inertia';


const { props: pageProps } = usePage();
const { auth } = pageProps;
const { errors } = pageProps; 

const props = defineProps({
  project: Object,
  reviews: Array,
  averageRating: String,
  applications: Array,
});

console.log(props.project);
const coverLetter = ref('')

const canApply = computed(() => {
  if (!auth?.user || !props.project) return false
  if (auth.user.role !== 'freelancer') return false
  if (props.project.creator_id === auth.user.id) return false
  if (currentUserApplication.value) return false

  return true
})

const currentUserApplication = computed(() => {
  if (!auth?.user || !Array.isArray(props.applications)) {
    return null
  }
  return props.applications.find(app => app.freelancer_id === auth.user.id)
})

const submitApplication = async () => {
  try {
    const formData = new FormData()
    formData.append('cover_letter', coverLetter.value)

    await axios.post(route('projects.apply', props.project.id), formData)
    alert('Application submitted!')
    location.reload();
  } catch (error) {
    console.error(error)
    alert('Failed to submit application.')
  }
}

const timeSincePosted = computed(() => {
  const postedDate = new Date(props.project.created_at);
  const currentDate = new Date();

  const diffTime = Math.abs(currentDate - postedDate);
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays === 0) {
      return "Today";
  } else if (diffDays === 1) {
      return "1 day ago";
  } else {
      return `${diffDays} days ago`;
  }
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
  form.post(route('reviews.addReview', props.project.id), {
    onSuccess: () => {
      form.reset();
    },
  });
};

const deleteReview = (review) => {
  console.log('deleteReview called with review:', review);
  if (confirm('Are you sure you want to delete this review?')) {
    form.delete(`/reviews/${review.ReviewID}`, {
      onSuccess: () => {
        Inertia.reload({ only: ['reviews', 'averageRating'] });
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
  console.log('editReview called with review:', review);
  editForm.id = review.ReviewID; 
  editForm.Rating = review.Rating;
  editForm.ReviewText = review.ReviewText;
};

const updateReview = () => {
  editForm.post(`/reviews/${editForm.id}/edit`, {
    onSuccess: () => {
      editForm.reset();
      editForm.id = null;
      Inertia.reload({ only: ['reviews', 'averageRating'] });
    },
  });
};


const deadline = computed(() => {
  if (!props.project.completion_date) {
    return "No deadline";
  }

  const date = new Date(props.project.completion_date);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
});
</script>
  