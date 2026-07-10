<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { computed } from 'vue';

const props = defineProps<{
    orders: {
        data: Array<{
            id: number;
            status: string;
            total: number;
            items_count: number;
            created_at: string;
        }>;
        current_page: number;
        last_page: number;
        next_page_url: string | null;
        prev_page_url: string | null;
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
    <Head title="Orders" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">My Orders</h1>

        <div v-if="orders.data.length === 0" class="py-12 text-center text-gray-500">
            <p class="text-lg">No orders yet.</p>
            <Link :href="route('products.index')" class="mt-4 inline-block font-semibold text-emerald-600 hover:text-emerald-700">
                Start Shopping
            </Link>
        </div>

        <div v-else class="space-y-4">
            <Link
                v-for="order in orders.data"
                :key="order.id"
                :href="route('orders.show', order.id)"
                class="block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Order #{{ order.id }}</p>
                        <p class="mt-1 text-sm text-gray-500">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                    </div>
                    <div class="text-right">
                        <span class="rounded-full px-3 py-1 text-sm font-medium" :class="statusColors[order.status] || 'bg-gray-100 text-gray-800'">
                            {{ statusLabel(order.status) }}
                        </span>
                        <p class="mt-1 font-bold text-emerald-700">
                            {{ Number(order.total).toFixed(2) }} EGP
                        </p>
                        <p class="text-sm text-gray-500">{{ order.items_count }} items</p>
                    </div>
                </div>
            </Link>
        </div>

        <div v-if="orders.last_page > 1" class="mt-8 flex justify-center gap-2">
            <Link
                v-if="orders.prev_page_url"
                :href="orders.prev_page_url"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Previous
            </Link>
            <Link
                v-if="orders.next_page_url"
                :href="orders.next_page_url"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Next
            </Link>
        </div>
    </div>
</template>
