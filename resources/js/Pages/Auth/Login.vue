<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Hero Section -->
        <div class="hero bg-base-200 min-h-screen">
            <div class="hero-content flex-col lg:flex-row-reverse w-full gap-10">

                <div class="text-center lg:text-left w-72">
                    <h1 class="text-5xl font-bold"> <span class="text-red-700">Login now!</span></h1>
                    <p class="py-6">
                        Discover your next opportunity with <span class="text-red-700 font-semibold">JobHunt</span> —
                        where talented freelancers meet real-world projects. Your career starts here.
                    </p>
                </div>

                <div class="card bg-base-100 w-full max-w-lg shrink-0 shadow-2xl">
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="login" value="Email or Username" />
                                <TextInput
                                    id="login"
                                    type="text"
                                    class="input input-bordered w-full mt-1"
                                    v-model="form.login"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <InputError class="mt-2" :message="form.errors.login" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password" value="Password" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="input input-bordered w-full mt-1"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                 <Link
                                v-if="canResetPassword"
                                :href="route('register')"
                                class="underline mr-10 text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                New to JobHunt?
                            </Link>

                                <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Forgot your password?
                            </Link>

                                <PrimaryButton
                                    class="ms-4 bg-red-700 hover:bg-red-800 "
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Log in
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    login: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>
