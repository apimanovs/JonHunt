<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import CharactedLimitedInput from "@/Components/CharactedLimitedInput.vue";
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const step = ref(1);

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});


const project = usePage().props.auth.user;

const form = useForm({
  title: "",
  niche: "",
  budget_type: "fixed",
  budget: 0,
  no_deadline: false,
  completion_date: "",
  description: "",
  tasks: "",
  expected_outcome: "",
  requirements: ""
});

const formattedBudget = computed(() => {
  return `$${Number(form.budget).toFixed(0)}`;

});

const niches = ['Technology', 'Health', 'Education', 'Finance', 'Entertainment', 'Other'];

const nextStep = () => {
  if (step.value < 4) {
    step.value++;
  }
};

const previousStep = () => {
  if (step.value > 1) {
    step.value--;
  }
};
</script>


<template>
  <Head title="Create Project" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Project</h2>
    </template>
    
    <div class="max-w-4xl mx-auto p-6 bg-white shadow sm:rounded-lg mt-6">
      <div class="mb-6 flex justify-center">
        <ul class="steps">
          <li class="step" :class="{ 'step-accent': step >= 1 }">Basic Info</li>
          <li class="step" :class="{ 'step-accent': step >= 2 }">Project Details</li>
          <li class="step" :class="{ 'step-accent': step >= 3 }">Price</li>
          <li class="step" :class="{ 'step-accent': step === 4 }">Review & Submit</li>
        </ul>
      </div>
      
      <form @submit.prevent="form.post(route('projects.store'))" class="mt-6 space-y-6">
        <div v-if="step === 1">
          <h2 class="text-2xl font-semibold">Step 1: Basic Information</h2>
          <p class="text-gray-600 my-5">Provide general information about your project.</p>

          <InputLabel for="title" value="Title" />
          <p class="text-sm text-gray-600">Choose a clear and concise title that reflects the project scope. (At least 60 characters)</p>
          <CharactedLimitedInput id="title" label="Title" v-model="form.title" :max="200" />

          <InputLabel for="niche" value="Niche" />
          <p class="text-sm text-gray-600">Select the industry or category that best fits your project.</p>
          <select id="niche" v-model="form.niche" required class="select select-bordered mt-1 block w-full my-10">
            <option v-for="niche in niches" :key="niche" :value="niche">{{ niche }}</option>
          </select>

          <div class="flex justify-between mt-6">
            <PrimaryButton @click="nextStep">Next Step</PrimaryButton>
          </div>
        </div>
        
        <div v-if="step === 2">
          <h2 class="text-2xl font-semibold">Project Details</h2>
          <p class="text-gray-600 mb-4">Describe the project, tasks, and expected outcome. So the freelancers can understand what you expect from them!</p>
          
          <InputLabel for="description" value="Description" />
          <p class="text-sm text-gray-600 mb-5">Give an overview of the project. Include its purpose, key details, and any other necessary information.</p>
          <CharactedLimitedInput id="description" label="Description" v-model="form.description" :max="1500" :textarea="true" />
          
          <InputLabel for="tasks" value="Project Tasks" />
          <p class="text-sm text-gray-600 mb-5">List the main tasks that need to be completed.</p>
          <CharactedLimitedInput id="tasks" label="Project Tasks" v-model="form.tasks" :max="1500" :textarea="true" />
          
          <InputLabel for="expected_outcome" value="Expected Outcome" />
          <p class="text-sm text-gray-600 mb-5">Describe what the final result should be.</p>
          <CharactedLimitedInput id="expected_outcome" label="Expected Outcome" v-model="form.expected_outcome" :max="1500" :textarea="true" />
          
          <InputLabel for="requirements" value="Freelancer Requirements" />
          <p class="text-sm text-gray-600 mb-5">List skills or qualifications that freelancers should have.</p>
          <CharactedLimitedInput id="requirements" label="Freelancer Requirements" v-model="form.requirements" :max="1500" :textarea="true" />
          
          <div class="flex justify-between mt-6">
            <PrimaryButton @click="previousStep">Previous Step</PrimaryButton>
            <PrimaryButton @click="nextStep">Next Step</PrimaryButton>
          </div>
        </div>

        <div v-if="step === 3">
          <h2 class="text-2xl font-semibold">Final Project Details</h2>
          <p class="text-gray-700 mb-4">
            In this step, you will finalize your project by specifying a deadline and setting the budget.
            If your project does not require a strict deadline, you can select the "No strict deadline" option.
          </p>
        
          <h3 class="text-lg font-semibold mt-4">Completion Date</h3>
          <p class="text-sm text-gray-600 mb-4">
            Select the date by which the project should be completed. If there is no strict deadline, 
            enable the option below.
          </p>
        
          <label class="flex items-center">
            <input type="checkbox" v-model="form.no_deadline" class="checkbox mr-2" />
            <span class="text-sm text-gray-700">No strict deadline</span>
          </label>
        
          <div v-if="!form.no_deadline" class="my-6">
            <InputLabel for="completion_date" value="Completion Date" />
            <p class="text-sm text-gray-600 mb-1">
              Choose a deadline for the project. The date must be within the next 12 months.
            </p>
            <TextInput id="completion_date" type="date" class="mt-1 block w-full" v-model="form.completion_date" required />
          </div>
        
          <h3 class="text-lg font-semibold mt-6">Budget</h3>
          <p class="text-sm text-gray-600 mb-2">
            Set an estimated budget for the project. If unsure, provide an approximate value. 
            (Use the slider to adjust the amount.)
          </p>
        
          <h3 class="text-lg font-semibold mt-6">Budget Type</h3>
          <p class="text-sm text-gray-600 mb-2">
            Specify whether the budget is fixed or based on an hourly rate.
          </p>
          
          <select
            id="budget_type"
            v-model="form.budget_type"
            required
            class="select select-bordered mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50"
          >
            <option value="fixed">Fixed Price</option>
            <option value="hourly">Hourly Rate</option>
          </select>
        
          <div class="mt-6">
            <InputLabel for="budget" value="Budget" />
            <p class="mt-2 text-sm text-gray-600">
              Specify the budget for your project using the slider below.
            </p>
            <span class="text-sm text-gray-500">Currency is US Dollars (10,000$ Max)</span>
            
            <div class="flex items-center space-x-4 mt-2">
              <input
                type="range"
                min="0"
                max="1000"
                step="1"
                v-model="form.budget"
                class="range range-error"
              />
              <span class="font-semibold">{{ formattedBudget }}</span>
            </div>
          </div>
        
          <div class="flex justify-between mt-6">
            <PrimaryButton @click="previousStep">Previous Step</PrimaryButton>
            <PrimaryButton @click="nextStep">Next Step</PrimaryButton>
          </div>
        </div>
        
        
        <div v-if="step === 4" class="p-6 bg-white rounded-lg shadow-md max-w-3xl mx-auto">
          <h2 class="text-2xl font-semibold text-gray-900 mb-4">Review Your Project</h2>
          <p class="text-gray-600 mb-4">
            Please review the information below before submitting your project. If anything needs to be changed, 
            go back to the previous steps.
          </p>
        
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Project Summary</h3>
            <ul class="list-disc list-inside space-y-2">
              <li class="break-words"><strong>Title:</strong> <span class="text-gray-700">{{ form.title }}</span></li>
              <InputError class="mt-2" :message="form.errors.title" />
              <li class="break-words"><strong>Niche:</strong> <span class="text-gray-700">{{ form.niche }}</span></li>
              <InputError class="mt-2" :message="form.errors.niche" />
              <li class="break-words"><strong>Budget:</strong> <span class="text-gray-700">{{ formattedBudget }} ({{ form.budget_type }})</span></li>
              <InputError class="mt-2" :message="form.errors.budget" />
              <InputError class="mt-2" :message="form.errors.budget_type" />
              <li v-if="form.completion_date" class="break-words"><strong>Completion Date:</strong> <span class="text-gray-700">{{ form.completion_date }}</span></li>
              <InputError class="mt-2" :message="form.errors.completion_date" />
              <li class="break-words whitespace-pre-line"><strong>Description:</strong> <span class="text-gray-700">{{ form.description }}</span></li>
              <InputError class="mt-2" :message="form.errors.description" />
              <li class="break-words whitespace-pre-line"><strong>Project Tasks:</strong> <span class="text-gray-700">{{ form.tasks }}</span></li>
              <InputError class="mt-2" :message="form.errors.tasks" />
              <li class="break-words whitespace-pre-line"><strong>Expected Outcome:</strong> <span class="text-gray-700">{{ form.expected_outcome }}</span></li>
              <InputError class="mt-2" :message="form.errors.expected_outcome" />
              <li class="break-words whitespace-pre-line"><strong>Freelancer Requirements:</strong> <span class="text-gray-700">{{ form.requirements }}</span></li>
              <InputError class="mt-2" :message="form.errors.requirements" />
            </ul>
        
          <div class="flex justify-between mt-6">
            <PrimaryButton @click="previousStep">Previous Step</PrimaryButton>
            <PrimaryButton type="submit">Submit Project</PrimaryButton>
          </div>

        </div>        
      </form>
    </div>
  </AuthenticatedLayout>
</template>


<style>

</style>