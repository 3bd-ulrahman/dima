<script setup lang="ts">
</script>

<template>
    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex items-center gap-2">
                    <img src="/logo.jpg" alt="Dima" class="h-8 w-8 rounded-full object-cover" />
                    <span class="text-lg font-bold text-emerald-700">Dima Supermarket</span>
                </div>
                <p class="text-sm text-gray-500">
                    &copy; {{ new Date().getFullYear() }} Dima. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
