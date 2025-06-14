<template>
    <Head title="Create Job Advertisement" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Job Advertisement
            </h2>
        </template>
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h2 class="text-2xl font-semibold text-gray-900">Advertisement Title</h2>
                    <p class="mt-2 text-sm text-gray-600">Write a title for your advertisement</p>
                    <form @submit.prevent="submitForm" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="title" value="Title" />
                            <CharactedLimitedInput
                                id="title"
                                v-model="form.title"
                                :max="50"
                                label="Title"
                                :textarea="false"
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Advertisement Description</h2>
                            <p class="mt-2 text-sm text-gray-600">Write a description for your advertisement</p>
                            <br />
                            <InputLabel for="description" value="Description" />
                            <CharactedLimitedInput
                                id="description"
                                v-model="form.description"
                                :max="1500"
                                label="Description"
                                :textarea="true"
                            />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Price</h2>
                            <p class="mt-2 text-sm text-gray-600">Set a price for the advertisement</p>
                            <br />
                            <InputLabel for="price" value="Price" />
                            <TextInput
                                id="price"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.price"
                                required
                                autocomplete="price"
                            />
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>

                        <div>
                            <InputLabel for="examples" value="Portfolio Files" />
                            <input
                                type="file"
                                id="examples"
                                name="examples[]"
                                multiple
                                @change="handleFileUpload"
                                class="mt-1 block w-full"
                            />
                            <label class="form-control w-full max-w-xs mt-2">
                                <span class="label-text-alt">Only a file of type: jpg, jpeg, png, pdf.</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors['examples']" />
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">Create Advertisement</PrimaryButton>
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

<script setup>
import AuthenticatedLayout from './../Layouts/AuthenticatedLayout.vue';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CharactedLimitedInput from '@/Components/CharactedLimitedInput.vue';


import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    price: '',
    examples: [], 
});

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    form.examples = files;
};

const submitForm = () => {
    form.post(route('jobAds.store'));
};
</script>

<style>

</style>
