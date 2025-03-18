
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';


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
    description: "",
    niche: "",
    no_deadline: false,
    completion_date: "",
    budget: 0,
    budget_type: "fixed"
});

const formattedBudget = computed(() => {
  return `$${Number(form.budget).toFixed(0)}`;

});

const niches = ['Technology', 'Health', 'Education', 'Finance', 'Entertainment', 'Other'];
</script>


<template>
    <Head title="Create project" />


    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Project
        </h2>
    </template>
      <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <div class="p-6 sm:p-8">
            <h2 class="text-2xl font-semibold text-gray-900">Advertisement Title</h2>
            <p class="mt-2 text-sm text-gray-600">Write a title for your project</p>
            <form @submit.prevent="form.post(route('projects.store'))" class="mt-6 space-y-6">

              <div>
                <p class="mt-2 text-sm text-gray-600">Write a title for your project (minimum 30 characters)</p>
                <InputLabel for="title" value="Title" />
                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.title"
                    required
                    autofocus
                    autocomplete="title"
                />
                <span class="label-text-alt">At least 30 symbols</span>
                <InputError class="mt-2" :message="form.errors.title" />
              </div>

              <div>
                <h2 class="text-2xl font-semibold text-gray-900">Advertisement Description</h2>
                <p class="mt-2 text-sm text-gray-600">
                  Write a detailed description for your project (minimum 100 characters). Include an overview, requirements, and expected outcomes.
                </p>               <br> 
               <InputLabel for="title" value="Description" />
               <textarea
                  id="description"
                  class="mt-1 block w-full h-32 focus-red-700 border "
                  v-model="form.description"
                  required
                  autocomplete="description"
                ></textarea>
                <InputError class="mt-2" :message="form.errors.description" />
                <label class="form-control w-full max-w-xs mt-2">
                  <span class="label-text-alt">At least 100 symbols</span>
                </label>
              </div>

              <br>
              <div>
                <h2 class="text-2xl font-semibold text-gray-900">Niche</h2>
                <p class="mt-2 text-sm text-gray-600">Pick a niche for your project</p>
               <br> <InputLabel for="niche" value="Niche" />
                <select
                  id="niche"
                  class=" select select-bordered mt-1 block w-full focus-red-800"
                  v-model="form.niche"
                  required
                  autofocus
                  autocomplete="niche"
                >
                  <option v-for="niche in niches" :key="niche" :value="niche">{{ niche }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.niche" />
              </div>
                <br>
              <div>
                <h2 class="text-2xl font-semibold text-gray-900">Select a completion date</h2>
                <p class="mt-2 text-sm text-gray-600">
                  Select a date when the project has to be done, or choose 'No strict deadline'
                </p>
                <br />
                <div class="flex items-center mt-2">
                  <input type="checkbox" checked="checked" id="no_deadline" v-model="form.no_deadline" class="checkbox mr-2">
                  <label for="no_deadline" class="text-sm text-gray-700">No strict deadline</label>
                </div>
                <br />
                <InputLabel for="completion_date" value="Completion Date" />
                <TextInput  
                v-if="!form.no_deadline"
                  id="completion_date"
                  type="date"
                  class="mt-1 block w-full"
                  v-model="form.completion_date"
                  required
                  autofocus
                  autocomplete="completion-date"
                />
                <InputError class="mt-2" :message="form.errors.completion_date" />
              </div>
              <br>

              <div class="mt-6">
                <h2 class="text-2xl font-semibold text-gray-900">Budget</h2>
                <p class="mt-2 text-sm text-gray-600">Specify the budget for your project</p>
                <div class="mt-2">
                  <InputLabel for="budget_type" value="Budget Type" />
                  <select
                    id="budget_type"
                    class="select select-bordered mt-1 block w-full"
                    v-model="form.budget_type"
                    required
                  >
                    <option value="fixed">Fixed Price</option>
                    <option value="hourly">Hourly Rate</option>
                  </select>
                  <InputError class="mt-2" :message="form.errors.budget_type" />
                </div>
                <div class="mt-6">
                  <h2 class="text-2xl font-semibold text-gray-900">Budget</h2>
                  <p class="mt-2 text-sm text-gray-600">
                    Specify the budget for your project using the slider below.
                  </p>
                  <div class="flex items-center space-x-4">
                    <input
                      type="range"
                      min="0"
                      max="1000"
                      step="1"
                      v-model="form.budget"
                      class="range"
                    />
                    <span class="font-semibold">{{ formattedBudget }}</span>
                  </div>
                  <InputError class="mt-2" :message="form.errors.budget" />
                </div>
              </div>

              <div class="mt-4" v-if="form.errors.max_projects">
                <p class="text-red-600">{{ form.errors.max_projects }}</p>
              </div>

              <div class="flex justify-end">
                <PrimaryButton :disabled="form.processing">Create a New Project</PrimaryButton>
                <Transition
                  enter-active-class="transition ease-in-out"
                  enter-from-class="opacity-0"
                  leave-active-class="transition ease-in-out"
                  leave-to-class="opacity-0"
                >
                  <p v-if="form.recentlySuccessful" class="ml-4 text-sm text-gray-600">Project created.</p>
                </Transition>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

<style>

</style>