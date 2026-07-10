<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    cart: {
        items: Array<{
            id: number;
            quantity: number;
            product: {
                id: number;
                name: string;
                price: number;
            };
        }>;
    } | null;
}>();

const form = useForm({
    delivery_address: '',
});

function submit() {
    form.post(route('orders.store'));
}
</script>

<template>
    <Head title="Checkout" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">Checkout</h1>

        <div v-if="!cart || cart.items.length === 0" class="py-12 text-center text-gray-500">
            <p class="text-lg">Your cart is empty.</p>
            <Link :href="route('products.index')" class="mt-4 inline-block font-semibold text-emerald-600 hover:text-emerald-700">
                Start Shopping
            </Link>
        </div>

        <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Delivery Details</h2>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <div>
                            <label for="address" class="mb-1 block text-sm font-medium text-gray-700">Delivery Address</label>
                            <textarea
                                id="address"
                                v-model="form.delivery_address"
                                rows="3"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                            ></textarea>
                            <p v-if="form.errors.delivery_address" class="mt-1 text-sm text-red-600">{{ form.errors.delivery_address }}</p>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-emerald-600 px-4 py-3 font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
                        >
                            Place Order
                        </button>
                    </form>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Order Summary</h2>
                <div class="mb-2 flex justify-between" v-for="item in cart.items" :key="item.id">
                    <span class="text-gray-600">{{ item.product.name }} x{{ item.quantity }}</span>
                    <span class="font-medium">EGP {{ (item.product.price * item.quantity).toFixed(2) }}</span>
                </div>
                <div class="mb-4 mt-4 flex justify-between border-t pt-4">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">
                        {{ cart.items.reduce((sum, item) => sum + item.product.price * item.quantity, 0).toFixed(2) }} EGP
                    </span>
                </div>
                <div class="mb-4 flex justify-between">
                    <span class="text-gray-600">Delivery Fee</span>
                    <span class="font-semibold">5.00 EGP</span>
                </div>
                <div class="flex justify-between border-t pt-4">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-lg font-bold text-emerald-700">
                        {{ (cart.items.reduce((sum, item) => sum + item.product.price * item.quantity, 0) + 5).toFixed(2) }} EGP
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
