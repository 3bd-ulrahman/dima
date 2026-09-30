<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppSidebar from '@/components/AppSidebar.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';

const page = usePage();

const flash = computed(
    () => page.props.flash as { success?: string; error?: string } | undefined,
);
</script>

<template>
    <SidebarProvider
        :style="{
            '--sidebar-width': 'calc(var(--spacing) * 72)',
            '--header-height': 'calc(var(--spacing) * 12)',
        }"
    >
        <AppSidebar variant="inset" />
        <SidebarInset>
            <SiteHeader />
            <div class="flex flex-1 flex-col">
                <div class="@container/main flex flex-1 flex-col gap-2">
                    <div class="py-4 md:py-6">
                        <slot />
                    </div>
                </div>
            </div>
        </SidebarInset>

        <div
            v-if="flash?.success"
            class="fixed top-4 right-4 z-50 flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm text-white shadow-lg"
            role="status"
        >
            {{ flash.success }}
        </div>

        <div
            v-if="flash?.error"
            class="fixed top-4 right-4 z-50 flex items-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm text-white shadow-lg"
            role="alert"
        >
            {{ flash.error }}
        </div>
    </SidebarProvider>
</template>
