<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    categories: Array<{ id: number; name: string; slug: string; products_count: number }>;
}>();
</script>

<template>
    <Head title="Categories" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">Categories</h1>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="category in categories"
                :key="category.id"
                :href="route('categories.show', category.slug)"
                class="flex flex-col items-center gap-4 rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm transition hover:shadow-md"
            >
                <span class="text-5xl">📦</span>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ category.name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ category.products_count }} products</p>
                </div>
            </Link>
        </div>
    </div>
</template>
