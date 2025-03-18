<script setup>
import { ref, onMounted } from 'vue';
import { usePage, useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed } from 'vue';

const { props: pageProps } = usePage();

const form = useForm({
  title: pageProps.project.title || '',
  description: pageProps.project.description || '',
  niche: pageProps.project.niche || '',
  no_deadline: !pageProps.project.completion_date,
  completion_date: pageProps.project.completion_date || '',
  budget: pageProps.project.budget || 0,
  budget_type: pageProps.project.budget_type || 'fixed',
});

const formattedBudget = computed(() => {
  return `$${Number(form.budget).toFixed(0)}`;
});

onMounted(() => {
    form.title = pageProps.project.title;
    form.description  = pageProps.project.description ;
    form.niche = pageProps.project.niche;
    form.completion_date = pageProps.project.completion_date;
    form.budget = pageProps.project.budget;
});

const submit = () => {
    form.put(route('projects.update', pageProps.project.id));
};

</script>


<template>
    <AuthenticatedLayout>
        <Head title="Edit Project" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit your project
            </h2>
        </template>

        <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Project</h2>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="title" value="Title" />
                    <p class="mt-2 text-sm text-gray-600">Write a title for your project (minimum 30 characters)</p>
                    <TextInput
                        id="title"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.title"
                        required
                        autofocus
                        autocomplete="title"
                  />
                    <span class="text-sm text-gray-500">At least 30 symbols</span>
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div>
                    <InputLabel for="description" value="Description" />
                    <p class="mt-2 text-sm text-gray-600">
                        Write a detailed description for your project (minimum 100 characters). Include an overview, requirements, and expected outcomes.
                    </p> 
                    <textarea
                      id="description"
                      class="mt-1 block w-full h-32 border"
                      v-model="form.description"
                      required
                      autocomplete="description"
                    ></textarea>
                    <InputError class="mt-2" :message="form.errors.description" />
                  </div>

                  <div>
                    <InputLabel for="niche" value="Niche" />
                    <p class="mt-2 text-sm text-gray-600">Pick a niche for your project</p>
                    <select
                      id="niche"
                      v-model="form.niche"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                      <option value="Technology">Technology</option>
                      <option value="Health">Health</option>
                      <option value="Education">Education</option>
                      <option value="Finance">Finance</option>
                      <option value="Entertainment">Entertainment</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.niche" />
                  </div>

                  
                  <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Select a completion date</h2>
                    <p class="mt-2 text-sm text-gray-600">
                      Select a date when the project has to be done, or choose 'No strict deadline'
                    </p>
                    <br />
                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="no_deadline" class="checkbox checkbox-xs mr-2" v-model="form.no_deadline" />
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

                  <div>
                    <InputLabel for="budget_type" value="Budget Type" />
                    <select
                      id="budget_type"
                      v-model="form.budget_type"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                    >
                      <option value="fixed">Fixed Price</option>
                      <option value="hourly">Hourly Rate</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.budget_type" />
                  </div>
                  <div>
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
                        class="range range-error hover:color-red-500"
                      />
                      <span class="font-semibold">{{ formattedBudget }}</span>
                    </div>
                    <InputError class="mt-2" :message="form.errors.budget" />
                  </div>

                <div class="flex justify-end">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Update Project
                    </PrimaryButton>
                    <Transition
                  enter-active-class="transition ease-in-out"
                  enter-from-class="opacity-0"
                  leave-active-class="transition ease-in-out"
                  leave-to-class="opacity-0"
                >
                  <p v-if="form.recentlySuccessful" class="ml-4 text-sm text-gray-600">Project edited</p>
                </Transition>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>