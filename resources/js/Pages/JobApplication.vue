<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
      <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ jobAd.Title }}</h2>
      <p class="text-md text-gray-600 mb-6 italic">
        You're about to apply for this freelance job opportunity. Make sure to carefully describe what you can offer and any specific needs you have regarding this project.
      </p>

      <p class="text-lg text-gray-700 mb-2">{{ jobAd.Description }}</p>
      <p class="text-xl font-semibold text-gray-600 mb-8">
        💰 Price: ${{ jobAd.Price }}
      </p>

      <form @submit.prevent="submitApplication">
        <div class="mb-4">
          <h3 class="text-lg font-medium text-gray-800 mb-2">Your Requirements</h3>
          <p class="text-sm text-gray-500 mb-3">
            Please provide any specific expectations, requests, or clarifications you want the client to consider before starting the job.
          </p>

          <CharactedLimitedInput
            id="requirements"
            v-model="form.requirements"
            :max="2000"
            label="Requirements"
            :textarea="true"
          />

          <div v-if="form.errors.requirements" class="text-red-500 text-sm mt-1">
            {{ form.errors.requirements }}
          </div>
        </div>

        <button
          type="submit"
          class="btn bg-red-600 text-white hover:bg-red-700 px-6 py-2 rounded-md mt-4"
        >
          🚀 Submit Application
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

  
  <script setup>
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import CharactedLimitedInput from "@/Components/CharactedLimitedInput.vue";

  import { useForm } from "@inertiajs/vue3";
  
  const props = defineProps({
    jobAd: Object,
  });
  
  const form = useForm({
    requirements: "",
  });
  

  function submitApplication() {
    if (!form.requirements.trim()) {
      form.errors.requirements = "Please enter your job requirements.";
      return;
    }
  
    form.post(`/jobs/${props.jobAd.id}/apply`, {
      onSuccess: () => {
        alert("Application submitted and payment processed!");
      },
      onError: (errors) => {
        alert(errors.message || "An error occurred during application.");
      },
    });
  }
  </script>
  