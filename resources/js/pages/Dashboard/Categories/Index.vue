<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    EllipsisVertical,
    ExternalLink,
    Pencil,
    Plus,
    Trash2,
} from '@lucide/vue';
import { ref } from 'vue';
import { route } from 'ziggy-js';

import CategoryFormSheet from '@/components/CategoryFormSheet.vue';
import DeleteCategoryDialog from '@/components/DeleteCategoryDialog.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { Category, CategoryFilters } from '@/types/category';
import type { Paginated } from '@/types/pagination';

const props = defineProps<{
    categories: Paginated<Category>;
    filters: CategoryFilters;
}>();

const PER_PAGE_OPTIONS = [10, 25, 50];

const formSheetOpen = ref(false);
const deleteDialogOpen = ref(false);
const selectedCategory = ref<Category | null>(null);

function applyFilters(changes: Partial<CategoryFilters>): void {
    router.get(
        route('dashboard.categories.index'),
        { ...props.filters, ...changes },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

function formatDate(value: string): string {
    return new Date(value).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function openCreate(): void {
    selectedCategory.value = null;
    formSheetOpen.value = true;
}

function openEdit(category: Category): void {
    selectedCategory.value = category;
    formSheetOpen.value = true;
}

function openDelete(category: Category): void {
    selectedCategory.value = category;
    deleteDialogOpen.value = true;
}
</script>

<template>
    <Head title="Categories" />

    <DashboardLayout>
        <div class="flex flex-col gap-4 px-4 lg:px-6">
            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex flex-col gap-1">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Categories
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        {{ props.categories.total }}
                        {{
                            props.categories.total === 1
                                ? 'category'
                                : 'categories'
                        }}
                        in total
                    </p>
                </div>

                <Button @click="openCreate">
                    <Plus />
                    New category
                </Button>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex flex-col gap-2">
                    <Label for="category-per-page">Per page</Label>
                    <Select
                        :model-value="String(props.filters.per_page)"
                        @update:model-value="
                            (value) => applyFilters({ per_page: Number(value) })
                        "
                    >
                        <SelectTrigger id="category-per-page" class="w-24">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="option in PER_PAGE_OPTIONS"
                                :key="option"
                                :value="String(option)"
                            >
                                {{ option }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead class="text-right">Products</TableHead>
                            <TableHead>Created</TableHead>
                            <TableHead class="w-0 text-right">
                                <span class="sr-only">Actions</span>
                            </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="category in props.categories.data"
                            :key="category.id"
                        >
                            <TableCell class="font-medium">
                                {{ category.name }}
                            </TableCell>
                            <TableCell class="text-right">
                                <Badge
                                    :variant="
                                        category.products_count > 0
                                            ? 'secondary'
                                            : 'outline'
                                    "
                                >
                                    {{ category.products_count }}
                                </Badge>
                            </TableCell>
                            <TableCell
                                class="whitespace-nowrap text-muted-foreground"
                            >
                                {{ formatDate(category.created_at) }}
                            </TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon-sm">
                                            <span class="sr-only">
                                                Actions for
                                                {{ category.name }}
                                            </span>
                                            <EllipsisVertical />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem
                                            @click="openEdit(category)"
                                        >
                                            <Pencil />
                                            Edit
                                        </DropdownMenuItem>
                                        <DropdownMenuItem as-child>
                                            <Link
                                                :href="
                                                    route(
                                                        'categories.show',
                                                        category.id,
                                                    )
                                                "
                                            >
                                                <ExternalLink />
                                                View on storefront
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            variant="destructive"
                                            :disabled="
                                                category.products_count > 0
                                            "
                                            @click="openDelete(category)"
                                        >
                                            <Trash2 />
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>

                        <TableEmpty
                            v-if="props.categories.data.length === 0"
                            :colspan="4"
                        >
                            <div class="flex flex-col items-center gap-2">
                                <p class="font-medium">No categories found</p>
                                <p class="text-sm text-muted-foreground">
                                    Create your first category to get started.
                                </p>
                                <Button
                                    size="sm"
                                    class="mt-2"
                                    @click="openCreate"
                                >
                                    <Plus />
                                    New category
                                </Button>
                            </div>
                        </TableEmpty>
                    </TableBody>
                </Table>
            </div>

            <div
                v-if="props.categories.last_page > 1"
                class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between"
            >
                <p class="text-sm text-muted-foreground">
                    Showing
                    {{ props.categories.from ?? 0 }}–{{
                        props.categories.to ?? 0
                    }}
                    of {{ props.categories.total }}
                </p>

                <div class="flex items-center gap-2">
                    <Button
                        v-if="!props.categories.prev_page_url"
                        variant="outline"
                        size="sm"
                        disabled
                    >
                        Previous
                    </Button>
                    <Button v-else variant="outline" size="sm" as-child>
                        <Link
                            :href="props.categories.prev_page_url"
                            preserve-scroll
                        >
                            Previous
                        </Link>
                    </Button>

                    <span class="text-sm font-medium">
                        Page {{ props.categories.current_page }} of
                        {{ props.categories.last_page }}
                    </span>

                    <Button
                        v-if="!props.categories.next_page_url"
                        variant="outline"
                        size="sm"
                        disabled
                    >
                        Next
                    </Button>
                    <Button v-else variant="outline" size="sm" as-child>
                        <Link
                            :href="props.categories.next_page_url"
                            preserve-scroll
                        >
                            Next
                        </Link>
                    </Button>
                </div>
            </div>
        </div>

        <CategoryFormSheet
            v-model:open="formSheetOpen"
            :category="selectedCategory"
        />

        <DeleteCategoryDialog
            v-model:open="deleteDialogOpen"
            :category="selectedCategory"
        />
    </DashboardLayout>
</template>
