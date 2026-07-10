<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Register" />

    <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
                <h1 class="mb-6 text-center text-2xl font-bold text-gray-900">Create Account</h1>

                <form @submit.prevent="submit" class="flex flex-col gap-4">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-700 disabled:opacity-50"
                    >
                        Register
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    Already have an account?
                    <Link :href="route('login')" class="font-semibold text-emerald-600 hover:text-emerald-700">Login</Link>
                </p>
            </div>
        </div>
    </div>
</template>
