<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps<{
    product: {
        id: number;
        name: string;
        slug: string;
        price: number;
        in_stock: boolean;
        category: { id: number; name: string };
        media: Array<{ original_url: string }>;
    };
}>();

function addToCart() {
    router.post(route('cart.add'), {
        product_id: props.product.id,
        quantity: 1,
    }, {
        preserveScroll: true
    });
}
</script>

<template>
    <div class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
        <div class="h-48 flex aspect-[4/3] items-center justify-center bg-gradient-to-br from-emerald-50 to-amber-50 p-6">
            <img
                v-if="props.product.media[0]?.original_url"
                :src="props.product.media[0]?.original_url"
                :alt="props.product.name"
                class="h-full w-full object-cover"
            />
            <span v-else class="text-5xl">🛒</span>
        </div>

        <div class="flex flex-1 flex-col gap-2 p-4">
            <Link :href="route('products.show', props.product.slug)" class="text-lg font-semibold text-gray-900 hover:text-emerald-600">
                {{ props.product.name }}
            </Link>

            <span class="text-sm text-gray-500">{{ props.product.category.name }}</span>

            <div class="mt-auto flex items-center justify-between">
                <span class="text-xl font-bold text-emerald-700">
                    {{ Number(props.product.price).toFixed(2) }} EGP
                </span>

                <button
                    v-if="props.product.in_stock"
                    @click="addToCart"
                    class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                >
                    Add to Cart
                </button>
                <span v-else class="rounded-lg bg-gray-200 px-3 py-2 text-sm font-medium text-gray-500">
                    Out of Stock
                </span>
            </div>
        </div>
    </div>
</template>
