<template>
  <Head title="Edit Freelance Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Freelance Profile</h2>
    </template>

    <div class="py-12 bg-gray-100 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-md rounded-lg">
          <h1 class="text-3xl font-bold text-gray-800 mb-6">Update Your Profile</h1>

          <p v-if="form.errors.general" class="text-red-600 text-sm mb-4">{{ form.errors.general }}</p>

          <form @submit.prevent="updateProfile" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
              <div class="relative">
                <input
                  type="text"
                  v-model="searchQuery"
                  @focus="showDropdown = true"
                  @blur="handleBlur"
                  placeholder="Start typing your country..."
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                  required
                />
                <ul
                  v-if="showDropdown && filteredCountries.length"
                  class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto"
                >
                  <li
                    v-for="country in filteredCountries"
                    :key="country"
                    @mousedown.prevent="selectCountry(country)"
                    class="p-2 hover:bg-indigo-500 hover:text-white cursor-pointer"
                  >
                    {{ country }}
                  </li>
                </ul>
              </div>
              <p v-if="form.errors.country" class="text-red-600 text-sm mt-1">{{ form.errors.country }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Specialization <span class="text-red-500">*</span></label>
              <select
                v-model="form.specialization"
                required
                class="form-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              >
                <option disabled value="">Select a specialization</option>
                <option value="Web Development">Web Development</option>
                <option value="Graphic Design">Graphic Design</option>
                <option value="Content Writing">Content Writing</option>
                <option value="Digital Marketing">Digital Marketing</option>
                <option value="SEO">SEO</option>
                <option value="Mobile App Development">Mobile App Development</option>
                <option value="UI/UX Design">UI/UX Design</option>
              </select>
              <p v-if="form.errors.specialization" class="text-red-600 text-sm mt-1">{{ form.errors.specialization }}</p>
            </div>

            <div class="flex space-x-4">
              <div class="w-1/2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Experience From</label>
                <input
                  v-model="form.experience_from"
                  type="number"
                  placeholder="e.g. 2019"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-red-500 focus:border-red-500"
                />
                <p v-if="form.errors.experience_from" class="text-red-600 text-sm mt-1">{{ form.errors.experience_from }}</p>
              </div>
              <div class="w-1/2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Experience To</label>
                <input
                  v-model="form.experience_to"
                  type="number"
                  placeholder="e.g. 2024"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-red-500 focus:border-red-500"
                />
                <p v-if="form.errors.experience_to" class="text-red-600 text-sm mt-1">{{ form.errors.experience_to }}</p>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
              <textarea
                v-model="form.bio"
                rows="4"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-red-500 focus:border-red-500"
              ></textarea>
              <p v-if="form.errors.bio" class="text-red-600 text-sm mt-1">{{ form.errors.bio }}</p>
            </div>

            <div class="text-right">
              <button
                type="submit"
                class="inline-flex items-center px-5 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                💾 Save Changes
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { countries } from '@/countries.js';

const { props } = usePage();

const form = useForm({
  specialization : props.freelancer.specialization ?? '',
  country        : props.freelancer.country ?? '',
  experience_from: props.freelancer.experience_from ?? '',
  experience_to  : props.freelancer.experience_to ?? '',
  bio            : props.freelancer.bio ?? '',
});

const searchQuery  = ref(form.country);
const showDropdown = ref(false);

const filteredCountries = computed(() => {
  if (!searchQuery.value) return countries;
  return countries.filter(c => c.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

function selectCountry(country) {
  form.country = country;
  searchQuery.value = country;
  showDropdown.value = false;
}

function handleBlur() {
  setTimeout(() => {
    showDropdown.value = false;
  }, 100);
}

function updateProfile() {
  form.country = searchQuery.value.trim();

  form.put(route('freelancers.update', props.auth.user.username), {
    preserveScroll: true,
    onSuccess: () => {
    },
  });
}
</script>
