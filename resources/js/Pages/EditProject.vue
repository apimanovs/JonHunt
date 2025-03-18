<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage, useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { props: pageProps } = usePage();

const form = useForm({
  title: pageProps.project.title || '',
  description: pageProps.project.description || '',
  tasks: pageProps.project.tasks || '',
  expected_outcome: pageProps.project.expected_outcome || '',
  requirements: pageProps.project.requirements || '',
  niche: pageProps.project.niche || '',
  no_deadline: !pageProps.project.completion_date,
  completion_date: pageProps.project.completion_date || '',
  budget: pageProps.project.budget || 0,
  budget_type: pageProps.project.budget_type || 'fixed',
});

const formattedBudget = computed(() => `$${Number(form.budget).toFixed(0)}`);

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

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-6 mb-6">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Project</h2>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Заголовок проекта -->
        <div>
          <InputLabel for="title" value="Title" />
          <p class="mt-2 text-sm text-gray-600">Write a title for your project (minimum 30 characters)</p>
          <TextInput id="title" type="text" v-model="form.title" required class="mt-1 block w-full" />
          <InputError class="mt-2" :message="form.errors.title" />
        </div>

        <!-- Описание проекта -->
        <div>
          <InputLabel for="description" value="Description" />
          <p class="mt-2 text-sm text-gray-600">
            Write a detailed description for your project (minimum 100 characters). Include an overview, requirements, and expected outcomes.
          </p>
          <textarea id="description" v-model="form.description" required class="textarea textarea-bordered w-full h-32"></textarea>
          <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <!-- Основные задачи проекта -->
        <div>
          <InputLabel for="tasks" value="Project Tasks" />
          <p class="mt-2 text-sm text-gray-600">List the main tasks that need to be completed.</p>
          <textarea id="tasks" v-model="form.tasks" class="textarea textarea-bordered w-full h-24"></textarea>
          <InputError class="mt-2" :message="form.errors.tasks" />
        </div>

        <!-- Ожидаемый результат -->
        <div>
          <InputLabel for="expected_outcome" value="Expected Outcome" />
          <p class="mt-2 text-sm text-gray-600">Describe what the final result should be.</p>
          <textarea id="expected_outcome" v-model="form.expected_outcome" class="textarea textarea-bordered w-full h-24"></textarea>
          <InputError class="mt-2" :message="form.errors.expected_outcome" />
        </div>

        <!-- Требования к исполнителю -->
        <div>
          <InputLabel for="requirements" value="Freelancer Requirements" />
          <p class="mt-2 text-sm text-gray-600">List skills or qualifications that freelancers should have.</p>
          <textarea id="requirements" v-model="form.requirements" class="textarea textarea-bordered w-full h-24"></textarea>
          <InputError class="mt-2" :message="form.errors.requirements" />
        </div>

        <!-- Ниша проекта -->
        <div>
          <InputLabel for="niche" value="Niche" />
          <p class="mt-2 text-sm text-gray-600">Pick a niche for your project.</p>
          <select id="niche" v-model="form.niche" required class="select select-bordered mt-1 block w-full">
            <option value="Technology">Technology</option>
            <option value="Health">Health</option>
            <option value="Education">Education</option>
            <option value="Finance">Finance</option>
            <option value="Entertainment">Entertainment</option>
          </select>
          <InputError class="mt-2" :message="form.errors.niche" />
        </div>

        <!-- Дедлайн проекта -->
        <div>
          <InputLabel for="completion_date" value="Completion Date" />
          <p class="mt-2 text-sm text-gray-600">
            Select a date when the project has to be done, or choose "No strict deadline".
          </p>
          <label class="flex items-center mt-2">
            <input type="checkbox" v-model="form.no_deadline" class="checkbox mr-2" />
            <span class="text-sm text-gray-700">No strict deadline</span>
          </label>
          <div v-if="!form.no_deadline" class="mt-4">
            <TextInput id="completion_date" type="date" v-model="form.completion_date" required class="mt-1 block w-full" />
            <InputError class="mt-2" :message="form.errors.completion_date" />
          </div>
        </div>

        <!-- Тип бюджета -->
        <div>
          <InputLabel for="budget_type" value="Budget Type" />
          <p class="mt-2 text-sm text-gray-600">Specify whether the budget is fixed or based on an hourly rate.</p>
          <select id="budget_type" v-model="form.budget_type" required class="select select-bordered mt-1 block w-full">
            <option value="fixed">Fixed Price</option>
            <option value="hourly">Hourly Rate</option>
          </select>
          <InputError class="mt-2" :message="form.errors.budget_type" />
        </div>

        <!-- Поле для ввода бюджета -->
        <div>
          <InputLabel for="budget" value="Budget" />
          <p class="mt-2 text-sm text-gray-600">
            Specify the budget for your project using the slider below.
          </p>
          <span class="text-sm text-gray-500">Currency is US Dollars (10,000$ Max)</span>
          <div class="flex items-center space-x-4 mt-2">
            <input type="range" min="0" max="10000" step="10" v-model="form.budget" class="range range-error" />
            <span class="font-semibold">{{ formattedBudget }}</span>
          </div>
          <InputError class="mt-2" :message="form.errors.budget" />
        </div>

        <div class="flex justify-end">
          <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Update Project
          </PrimaryButton>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
