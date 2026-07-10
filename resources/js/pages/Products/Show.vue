<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps<{
    product: {
        id: number;
        name: string;
        slug: string;
        barcode: string;
        price: number;
        in_stock: boolean;
        category: { id: number; name: string; slug: string };
        created_at: string;
    };
    related: Array<{
        id: number;
        name: string;
        slug: string;
        price: number;
        in_stock: boolean;
        category: { id: number; name: string };
    }>;
}>();

const form = useForm({
    product_id: props.product.id,
    quantity: 1,
});

function addToCart() {
    form.post(route('cart.add'));
}
</script>

<template>
    <Head :title="product.name" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <Link :href="route('products.index')" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
            &larr; Products
        </Link>

        <div class="mt-6 grid grid-cols-1 gap-12 lg:grid-cols-2">
            <div class="flex aspect-square items-center justify-center rounded-xl bg-gradient-to-br from-emerald-50 to-amber-50">
                <span class="text-8xl">🛒</span>
            </div>

            <div>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800">
                    {{ product.category.name }}
                </span>
                <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ product.name }}</h1>
                <p class="mt-2 text-sm text-gray-500">Barcode: {{ product.barcode }}</p>

                <p class="mt-6 text-4xl font-bold text-emerald-700">
                    {{ Number(product.price).toFixed(2) }} EGP
                </p>

                <div class="mt-8 flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Qty:</span>
                        <input
                            v-model="form.quantity"
                            type="number"
                            min="1"
                            max="99"
                            class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-center focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                    </label>

                    <button
                        v-if="product.in_stock"
                        @click="addToCart"
                        :disabled="form.processing"
                        class="flex-1 rounded-lg bg-emerald-600 px-6 py-3 text-lg font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
                    >
                        Add to Cart
                    </button>
                    <span v-else class="rounded-lg bg-gray-200 px-6 py-3 text-lg font-semibold text-gray-500">
                        Out of Stock
                    </span>
                </div>
            </div>
        </div>

        <section v-if="related.length" class="mt-16">
            <h2 class="mb-6 text-2xl font-bold text-gray-900">Related Products</h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <ProductCard v-for="p in related" :key="p.id" :product="p" />
            </div>
        </section>
    </div>
</template>
