<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    order: {
        id: number;
        subtotal: number;
        delivery_fee: number;
        total: number;
        delivery_address: string;
        status: string;
        created_at: string;
        items: Array<{
            id: number;
            quantity: number;
            price_at_purchase: number;
            product: {
                id: number;
                name: string;
                slug: string;
                category: { id: number; name: string };
            } | null;
        }>;
    };
}>();

const statusColors: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-800',
    preparing: 'bg-blue-100 text-blue-800',
    out_for_delivery: 'bg-purple-100 text-purple-800',
    delivered: 'bg-emerald-100 text-emerald-800',
    cancelled: 'bg-red-100 text-red-800',
};

function statusLabel(status: string): string {
    return status.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
}
</script>

<template>
    <Head :title="`Order #${order.id}`" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <Link :href="route('orders.index')" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
            &larr; Orders
        </Link>

        <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Order #{{ order.id }}</h1>
                            <p class="text-sm text-gray-500">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-sm font-medium" :class="statusColors[order.status] || 'bg-gray-100 text-gray-800'">
                            {{ statusLabel(order.status) }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between border-b pb-4 last:border-0">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-50 to-amber-50">
                                    <span class="text-xl">🛒</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ item.product?.name ?? 'Deleted Product' }}</p>
                                    <p class="text-sm text-gray-500">{{ item.product?.category?.name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">${{ Number(item.price_at_purchase).toFixed(2) }} x{{ item.quantity }}</p>
                                <p class="text-sm text-gray-500">${{ (item.price_at_purchase * item.quantity).toFixed(2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Order Details</h2>
                <div class="mb-4">
                    <h3 class="mb-2 text-sm font-medium text-gray-500">Delivery Address</h3>
                    <p class="text-gray-900">{{ order.delivery_address }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">${{ Number(order.subtotal).toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Fee</span>
                        <span class="font-medium">${{ Number(order.delivery_fee).toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-emerald-700">${{ Number(order.total).toFixed(2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
