<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();

const flash = computed(() => page.props.flash as { success?: string; error?: string } | undefined);
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <Navbar />

        <div v-if="flash?.success" class="fixed top-4 right-4 z-50 rounded-lg bg-emerald-600 px-4 py-3 text-white shadow-lg">
            {{ flash.success }}
        </div>

        <div v-if="flash?.error" class="fixed top-4 right-4 z-50 rounded-lg bg-red-600 px-4 py-3 text-white shadow-lg">
            {{ flash.error }}
        </div>

        <main class="flex-1">
            <slot />
        </main>

        <Footer />
    </div>
</template>
