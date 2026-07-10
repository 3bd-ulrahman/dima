<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import ProductCard from '@/Components/ProductCard.vue';

defineProps<{
    category: { id: number; name: string; slug: string; products_count: number };
    products: {
        data: Array<{
            id: number;
            name: string;
            slug: string;
            price: number;
            in_stock: boolean;
            category: { id: number; name: string };
            media: Array<{ original_url: string }>;
        }>;
        current_page: number;
        last_page: number;
        next_page_url: string | null;
        prev_page_url: string | null;
    };
}>();
</script>

<template>
    <Head :title="category.name" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <Link :href="route('categories.index')" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                &larr; Categories
            </Link>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ category.name }}</h1>
            <p class="mt-1 text-gray-500">{{ category.products_count }} products</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
        </div>

        <div v-if="products.last_page > 1" class="mt-8 flex justify-center gap-2">
            <Link
                v-if="products.prev_page_url"
                :href="products.prev_page_url"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Previous
            </Link>
            <Link
                v-if="products.next_page_url"
                :href="products.next_page_url"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Next
            </Link>
        </div>
    </div>
</template>
