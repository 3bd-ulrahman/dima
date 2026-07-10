<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    cart: {
        id: number;
        items: Array<{
            id: number;
            quantity: number;
            product: {
                id: number;
                name: string;
                slug: string;
                price: number;
                in_stock: boolean;
                category: { id: number; name: string };
            };
        }>;
    } | null;
}>();

function updateQuantity(itemId: number, quantity: number) {
    router.patch(route('cart.update', itemId), { quantity }, { preserveScroll: true });
}

function removeItem(itemId: number) {
    router.delete(route('cart.remove', itemId), { preserveScroll: true });
}
</script>

<template>
    <Head title="Cart" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">Shopping Cart</h1>

        <div v-if="!cart || cart.items.length === 0" class="py-12 text-center text-gray-500">
            <p class="text-lg">Your cart is empty.</p>
            <Link :href="route('products.index')" class="mt-4 inline-block font-semibold text-emerald-600 hover:text-emerald-700">
                Start Shopping
            </Link>
        </div>

        <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div v-for="item in cart.items" :key="item.id" class="mb-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-50 to-amber-50">
                            <span class="text-2xl">🛒</span>
                        </div>

                        <div class="flex-1">
                            <Link :href="route('products.show', item.product.slug)" class="font-semibold text-gray-900 hover:text-emerald-600">
                                {{ item.product.name }}
                            </Link>
                            <p class="text-sm text-gray-500">{{ item.product.category.name }}</p>
                            <p class="font-semibold text-emerald-700">${{ Number(item.product.price).toFixed(2) }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <select
                                :value="item.quantity"
                                @change="updateQuantity(item.id, Number(($event.target as HTMLSelectElement).value))"
                                class="rounded-lg border border-gray-300 px-2 py-1 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                            >
                                <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                            </select>

                            <button @click="removeItem(item.id)" class="text-red-500 hover:text-red-700">
                                <span class="text-lg">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Order Summary</h2>
                <div class="mb-4 flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">
                        ${{ cart.items.reduce((sum, item) => sum + item.product.price * item.quantity, 0).toFixed(2) }}
                    </span>
                </div>
                <div class="mb-4 flex justify-between">
                    <span class="text-gray-600">Delivery Fee</span>
                    <span class="font-semibold">$5.00</span>
                </div>
                <div class="mb-6 flex justify-between border-t pt-4">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-lg font-bold text-emerald-700">
                        ${{ (cart.items.reduce((sum, item) => sum + item.product.price * item.quantity, 0) + 5).toFixed(2) }}
                    </span>
                </div>
                <Link
                    :href="route('orders.checkout')"
                    class="block w-full rounded-lg bg-emerald-600 px-4 py-3 text-center font-semibold text-white hover:bg-emerald-700"
                >
                    Proceed to Checkout
                </Link>
            </div>
        </div>
    </div>
</template>
