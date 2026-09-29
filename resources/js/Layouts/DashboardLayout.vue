<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
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
    </SidebarProvider>
</template>
