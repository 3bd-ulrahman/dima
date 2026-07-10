<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import ProductCard from '@/Components/ProductCard.vue';

defineProps<{
    categories: Array<{ id: number; name: string; slug: string; products_count: number }>;
    products: Array<{
        id: number;
        name: string;
        slug: string;
        price: number;
        in_stock: boolean;
        category: { id: number; name: string };
        media: Array<{ original_url: string }>;
    }>;
}>();
</script>

<template>
    <Head title="Home" />

    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 to-emerald-800 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-6 text-center">
                <h1 class="text-5xl font-bold tracking-tight sm:text-6xl">
                    Fresh Groceries,<br />
                    <span class="text-amber-300">Delivered to You</span>
                </h1>
                <p class="max-w-2xl text-lg text-emerald-100">
                    Shop from the widest selection of fresh produce, pantry staples, and everyday essentials.
                    Quality you can trust, prices you'll love.
                </p>
                <div class="flex gap-4">
                    <Link
                        :href="route('products.index')"
                        class="rounded-lg bg-amber-400 px-8 py-3 text-lg font-semibold text-emerald-900 hover:bg-amber-300"
                    >
                        Shop Now
                    </Link>
                    <Link
                        :href="route('categories.index')"
                        class="rounded-lg border-2 border-white px-8 py-3 text-lg font-semibold text-white hover:bg-white/10"
                    >
                        Browse Categories
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16" v-if="categories.length">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="mb-8 text-3xl font-bold text-gray-900">Shop by Category</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('categories.show', category.slug)"
                    class="flex flex-col items-center gap-3 rounded-xl border border-gray-200 bg-white p-6 text-center shadow-sm transition hover:shadow-md"
                >
                    <span class="text-3xl">📦</span>
                    <span class="font-semibold text-gray-900">{{ category.name }}</span>
                    <span class="text-sm text-gray-500">{{ category.products_count }} items</span>
                </Link>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-900">New Arrivals</h2>
                <Link :href="route('products.index')" class="font-semibold text-emerald-600 hover:text-emerald-700">
                    View All &rarr;
                </Link>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <ProductCard v-for="product in products" :key="product.id" :product="product" />
            </div>
        </div>
    </section>

    <section class="bg-emerald-700 py-16 text-white">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-4 text-3xl font-bold">Why Shop at Dima?</h2>
            <div class="mt-8 grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="flex flex-col items-center gap-3">
                    <span class="text-4xl">🥬</span>
                    <h3 class="text-xl font-semibold">Fresh Guaranteed</h3>
                    <p class="text-emerald-200">We source the freshest produce daily from local farms.</p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <span class="text-4xl">🚚</span>
                    <h3 class="text-xl font-semibold">Fast Delivery</h3>
                    <p class="text-emerald-200">Free delivery on orders over EGP 50. Get it within 2 hours.</p>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <span class="text-4xl">💚</span>
                    <h3 class="text-xl font-semibold">Best Prices</h3>
                    <p class="text-emerald-200">We match prices and offer daily deals on hundreds of items.</p>
                </div>
            </div>
        </div>
    </section>
</template>
