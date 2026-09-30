<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import {
    Camera,
    ChartNoAxesColumn,
    LayoutDashboard,
    Database,
    WandSparkles,
    FileText,
    Folder,
    CircleQuestionMark,
    Layers,
    List,
    FileChartColumn,
    Search,
    Settings,
    Tags,
    Users,
} from '@lucide/vue';
import { computed } from 'vue';
import { route } from 'ziggy-js';

import NavDocuments from '@/components/NavDocuments.vue';
import NavMain from '@/components/NavMain.vue';
import NavSecondary from '@/components/NavSecondary.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

const page = usePage();

const currentPath = computed(
    () => page.url.replace(/^https?:\/\/[^/]+/, '').split('?')[0],
);

const data = computed(() => ({
    user: {
        name: 'shadcn',
        email: 'm@example.com',
        avatar: '/avatars/shadcn.jpg',
    },
    navMain: [
        {
            title: 'Dashboard',
            url: route('dashboard.index'),
            icon: LayoutDashboard,
            isActive: currentPath.value === route('dashboard.index'),
        },
        {
            title: 'Categories',
            url: route('dashboard.categories.index'),
            icon: Tags,
            isActive: currentPath.value.startsWith(
                route('dashboard.categories.index'),
            ),
        },
        {
            title: 'Lifecycle',
            url: '#',
            icon: List,
        },
        {
            title: 'Analytics',
            url: '#',
            icon: ChartNoAxesColumn,
        },
        {
            title: 'Projects',
            url: '#',
            icon: Folder,
        },
        {
            title: 'Team',
            url: '#',
            icon: Users,
        },
    ],
    navClouds: [
        {
            title: 'Capture',
            icon: Camera,
            isActive: true,
            url: '#',
            items: [
                {
                    title: 'Active Proposals',
                    url: '#',
                },
                {
                    title: 'Archived',
                    url: '#',
                },
            ],
        },
        {
            title: 'Proposal',
            icon: FileText,
            url: '#',
            items: [
                {
                    title: 'Active Proposals',
                    url: '#',
                },
                {
                    title: 'Archived',
                    url: '#',
                },
            ],
        },
        {
            title: 'Prompts',
            icon: WandSparkles,
            url: '#',
            items: [
                {
                    title: 'Active Proposals',
                    url: '#',
                },
                {
                    title: 'Archived',
                    url: '#',
                },
            ],
        },
    ],
    navSecondary: [
        {
            title: 'Settings',
            url: '#',
            icon: Settings,
        },
        {
            title: 'Get Help',
            url: '#',
            icon: CircleQuestionMark,
        },
        {
            title: 'Search',
            url: '#',
            icon: Search,
        },
    ],
    documents: [
        {
            name: 'Data Library',
            url: '#',
            icon: Database,
        },
        {
            name: 'Reports',
            url: '#',
            icon: FileChartColumn,
        },
        {
            name: 'Word Assistant',
            url: '#',
            icon: FileText,
        },
    ],
}));
</script>

<template>
    <Sidebar collapsible="offcanvas">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        as-child
                        class="data-[slot=sidebar-menu-button]:!p-1.5"
                    >
                        <a href="#">
                            <Layers class="!size-5" />
                            <span class="text-base font-semibold"
                                >Acme Inc.</span
                            >
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="data.navMain" />
            <NavDocuments :items="data.documents" />
            <NavSecondary :items="data.navSecondary" class="mt-auto" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser :user="data.user" />
        </SidebarFooter>
    </Sidebar>
</template>
