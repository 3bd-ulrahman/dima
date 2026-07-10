<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const page = usePage();

const user = computed(() => page.props.auth?.user ?? null);
const cartCount = computed(() => (page.props as any).cartCount ?? 0);
</script>

<template>
    <nav class="sticky top-0 z-40 border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <Link :href="route('home')" class="flex items-center gap-3">
                <img src="/logo.jpg" alt="Dima" class="h-10 w-10 rounded-full object-cover" />
                <span class="text-xl font-bold text-emerald-700">Dima</span>
            </Link>

            <div class="flex items-center gap-6">
                <Link :href="route('categories.index')" class="font-medium text-gray-700 hover:text-emerald-600">
                    Categories
                </Link>
                <Link :href="route('products.index')" class="font-medium text-gray-700 hover:text-emerald-600">
                    Products
                </Link>

                <template v-if="user">
                    <Link :href="route('cart.index')" class="relative font-medium text-gray-700 hover:text-emerald-600">
                        Cart
                        <span v-if="cartCount > 0" class="absolute -top-2 -right-4 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-xs text-white">
                            {{ cartCount }}
                        </span>
                    </Link>
                    <Link :href="route('orders.index')" class="font-medium text-gray-700 hover:text-emerald-600">
                        Orders
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="font-medium text-gray-700 hover:text-emerald-600">
                        Logout
                    </Link>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="font-medium text-gray-700 hover:text-emerald-600">
                        Login
                    </Link>
                    <Link :href="route('register')" class="rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-700">
                        Register
                    </Link>
                </template>
            </div>
        </div>
    </nav>
</template>
