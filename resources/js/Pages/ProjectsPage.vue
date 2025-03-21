<template>
  <Head title="Projects Information" />
  <AuthenticatedLayout>
    <div class="my-10 max-w-5xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 sm:p-8">
      <!-- Title -->
      <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 break-words">
        {{ project.title }}
      </h2>

      <!-- Creator Info -->
      <div class="creator-info flex flex-wrap items-center mt-4 mb-6">
        <a
          v-if="project.creator && project.creator.avatar"
          :href="`/user/${project.creator.username}`"
          class="flex items-center"
        >
          <img
            :src="project.creator.avatar.photo_url"
            alt="Avatar"
            class="w-10 h-10 rounded-full mr-3 border"
          />
        </a>
        <a
          v-else
          :href="`/user/${project.creator.username}`"
          class="flex items-center"
        >
          <div class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3">
            {{ project.creator.name.charAt(0).toUpperCase() }}
          </div>
        </a>
        <a
          :href="`/user/${project.creator.username}`"
          class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-500 transition break-words"
        >
          {{ project.creator.name }}
          <span
            v-if="project.creator.role === 'freelancer'"
            class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full"
          >
            Freelancer
          </span>
        </a>
      </div>

      <!-- Project Details -->
      <div class="space-y-4 text-gray-800 dark:text-gray-100">
        <h3 class="text-3xl font-semibold border-b pb-1 mb-2">Project Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="stat bg-gray-50 dark:bg-gray-700 rounded shadow p-4">
            <div class="stat-title text-sm text-gray-500">Total budget</div>
            <div class="stat-value text-primary text-3xl break-words">${{ project.budget }}</div>
          </div>

          <div class="stat bg-gray-50 dark:bg-gray-700 rounded shadow p-4">
            <div class="stat-title text-sm text-gray-500">Deadline</div>
            <div class="stat-value text-secondary text-3xl break-words">{{ deadline }}</div>
          </div>

          <div class="stat bg-gray-50 dark:bg-gray-700 rounded shadow p-4">
            <div class="stat-title text-sm text-gray-500">Niche</div>
            <div class="stat-value text-2xl break-words">{{ project.niche }}</div>
          </div>
        </div>
      </div>


      <div class="grid gap-6 mt-6">
        <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900 p-5 rounded shadow">
          <h4 class="text-blue-700 dark:text-blue-200 text-xl font-semibold flex items-center gap-2">
            Expected Outcome
          </h4>
          <p class="text-gray-800 text-lg dark:text-gray-100 mt-1">{{ project.expected_outcome }}</p>
        </div>
      
        <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900 p-5 rounded shadow">
          <h4 class="text-green-700 dark:text-green-200 text-xl font-semibold flex items-center gap-2">
            Requirements
          </h4>
          <p class="text-gray-800 text-lg dark:text-gray-100 mt-1">{{ project.requirements }}</p>
        </div>
      
        <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900 p-5 rounded shadow">
          <h4 class="text-yellow-700 dark:text-yellow-200 text-xl font-semibold flex items-center gap-2">
            Tasks
          </h4>
          <p class="text-gray-800 text-lg dark:text-gray-100 mt-1 whitespace-pre-line">{{ project.tasks }}</p>
        </div>
      </div>
      
      



      <!-- Current User Application -->
      <div class="lg:w-full w-full">
        <div v-if="canApply" class="mt-8 lg:mt-0 bg-blue-50 dark:bg-gray-900 p-6 rounded shadow">
          <h3 class="text-xl font-semibold mb-4 text-blue-700 dark:text-blue-300">Apply to this Project</h3>
          <form @submit.prevent="submitApplication" class="space-y-4">
            <div>
              <label class="block font-semibold">Cover Letter</label>
              <textarea
                v-model="coverLetter"
                class="border w-full p-2 rounded"
                rows="4"
                placeholder="Explain why you are the best fit..."
                required
              ></textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              Submit Application
            </button>
          </form>
        </div>

        <div
          v-else-if="currentUserApplication"
          class="mt-8 lg:mt-0 bg-gray-50 dark:bg-gray-700 p-6 rounded border dark:border-gray-600"
        >
          <h3 class="text-xl font-semibold mb-2">Your Application</h3>
          <p><strong>Status:</strong> {{ currentUserApplication.status }}</p>
          <p class="mt-1"><strong>Cover Letter:</strong> {{ currentUserApplication.cover_letter }}</p>
        </div>
      </div>
      <!-- Reviews List -->
      <div class="mt-6">
        <h3 class="text-xl font-semibold">Reviews</h3>
        <ul class="mt-4 space-y-4">
          <li
            v-for="review in reviews"
            :key="review.id"
            class="p-4 bg-white dark:bg-gray-800 shadow rounded border border-gray-200 dark:border-gray-700"
          >
            <div v-if="editForm.id === review.ReviewID">
              <div class="mb-2">
                <label class="block font-medium">Rating</label>
                <select v-model="editForm.Rating" class="w-full p-1 border rounded">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="block font-medium">Review</label>
                <textarea v-model="editForm.ReviewText" class="w-full p-2 border rounded"></textarea>
                <InputError :message="form.errors.ReviewText" class="mt-1" />
              </div>
              <div class="flex gap-2">
                <PrimaryButton @click="updateReview" class="bg-blue-600 text-white">Update</PrimaryButton>
                <button @click="cancelEdit" class="text-red-500 hover:underline">Cancel</button>
              </div>
            </div>
            <div v-else>
              <p class="text-gray-900 dark:text-white">{{ review.ReviewText }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ review.user.name }} (Rating: {{ review.Rating }})
                <span v-if="auth.user && review.user.id === auth.user.id">
                  <button @click="editReview(review)" class="ml-2 text-blue-500 hover:underline">Edit</button>
                  <button @click="deleteReview(review)" class="ml-2 text-red-500 hover:underline">Delete</button>
                </span>
              </p>
            </div>
          </li>
        </ul>
      </div>

      <!-- Add Review Form -->
      <div class="mt-10">
        <h3 class="text-xl font-semibold">Add a Review</h3>
        <form @submit.prevent="submitReview" class="space-y-4">
          <div>
            <label class="block font-medium">Rating</label>
            <select v-model="form.Rating" class="w-full p-2 border rounded">
              <option disabled selected>Choose rating</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
          </div>
          <div>
            <label class="block font-medium">Review</label>
            <textarea v-model="form.ReviewText" class="w-full p-2 border rounded"></textarea>
            <InputError class="mt-1" :message="form.errors.ReviewText" />
          </div>
          <PrimaryButton type="submit" class="bg-red-600 text-white">Post Review</PrimaryButton>
        </form>
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
  