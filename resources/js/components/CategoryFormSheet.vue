<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { route } from 'ziggy-js';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import type { Category, CategoryPayload } from '@/types/category';

const props = defineProps<{
    open: boolean;
    category: Category | null;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const isEditing = computed(() => props.category !== null);

const form = useForm<CategoryPayload>({
    name: '',
});

const formUrl = computed(() => {
    if (props.category) {
        return route('dashboard.categories.update', props.category.id);
    }

    return route('dashboard.categories.store');
});

watch(
    () => props.open,
    (open) => {
        if (open) {
            form.name = props.category?.name ?? '';
            form.clearErrors();
        }
    },
);

function close(): void {
    emit('update:open', false);
}

function submit(): void {
    form.submit(isEditing.value ? 'put' : 'post', formUrl.value, {
        preserveScroll: true,
        onSuccess: close,
    });
}
</script>

<template>
    <Sheet :open="open" @update:open="emit('update:open', $event)">
        <SheetContent class="sm:max-w-md">
            <SheetHeader>
                <SheetTitle>
                    {{ isEditing ? 'Edit category' : 'New category' }}
                </SheetTitle>
                <SheetDescription>
                    {{
                        isEditing
                            ? 'Update the name of this category.'
                            : 'Create a category to group products under.'
                    }}
                    The URL slug is generated automatically from the name.
                </SheetDescription>
            </SheetHeader>

            <form class="flex flex-col gap-4 px-4" @submit.prevent="submit">
                <div class="flex flex-col gap-2">
                    <Label for="category-name">Name</Label>
                    <Input
                        id="category-name"
                        v-model="form.name"
                        placeholder="e.g. Fresh Produce"
                        autofocus
                    />
                    <p v-if="form.errors.name" class="text-sm text-destructive">
                        {{ form.errors.name }}
                    </p>
                </div>

                <SheetFooter class="mt-2">
                    <Button type="button" variant="outline" @click="close">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save' }}
                    </Button>
                </SheetFooter>
            </form>
        </SheetContent>
    </Sheet>
</template>
