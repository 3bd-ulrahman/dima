<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps<{
    products: {
        data: Array<{
            id: number;
            name: string;
            slug: string;
            price: number;
            in_stock: boolean;
            category: { id: number; name: string };
        }>;
        current_page: number;
        last_page: number;
        next_page_url: string | null;
        prev_page_url: string | null;
    };
    categories: Array<{ id: number; name: string }>;
    filters: { search?: string; category?: string; sort?: string };
}>();

function applyFilter(key: string, value: string) {
    router.get(route('products.index'), { ...props.filters, [key]: value }, { preserveState: true, replace: true });
}
</script>

<template>
    <Head title="Products" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">All Products</h1>

        <div class="mb-8 flex flex-wrap gap-4">
            <input
                :value="filters.search ?? ''"
                @input="applyFilter('search', ($event.target as HTMLInputElement).value)"
                type="text"
                placeholder="Search products..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            />

            <select
                :value="filters.category ?? ''"
                @change="applyFilter('category', ($event.target as HTMLSelectElement).value)"
                class="rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            >
                <option value="">All Categories</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>

            <select
                :value="filters.sort ?? ''"
                @change="applyFilter('sort', ($event.target as HTMLSelectElement).value)"
                class="rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            >
                <option value="">Sort by</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="newest">Newest First</option>
            </select>
        </div>

        <div v-if="products.data.length === 0" class="py-12 text-center text-gray-500">
            No products found.
        </div>

        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
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
