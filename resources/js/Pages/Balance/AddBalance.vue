<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Deposit Funds</h2>
    </template>

    <div class="max-w-4xl mx-auto mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">
      <div class="text-center mb-8">
        <p class="text-lg text-gray-700 dark:text-gray-300">
          Manage your balance easily by depositing funds.
        </p>
      </div>

      <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
        Current Balance: <span class="text-green-600">${{ Number(balance).toFixed(2) }}</span>
      </h3>

      <form @submit.prevent="submitForm" class="space-y-6">
        <div>
          <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Deposit Amount
          </label>
          <input
            v-model="form.amount"
            type="number"
            id="amount"
            class="mt-2 block w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-red-300 focus:border-red-500 dark:bg-gray-700 dark:text-white"
            placeholder="Enter the amount you wish to deposit"
            min="1"
            step="0.01"
          />
          <div v-if="form.errors.amount" class="text-red-500 text-sm mt-1">
            {{ form.errors.amount }}
          </div>
        </div>

        <div class="flex justify-center">
          <button
            type="submit"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition"
          >
            Deposit Now
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  balance: {
    type: Number,
    required: true,
  },
});

const form = useForm({
  amount: '',
});

const submitForm = () => {
  form.post(route('balance.store'), {
    onSuccess: () => {
    },
  });
};
</script>
