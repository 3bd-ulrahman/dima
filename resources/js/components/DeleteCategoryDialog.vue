<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { route } from 'ziggy-js';

import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import type { Category } from '@/types/category';

const props = defineProps<{
    open: boolean;
    category: Category | null;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const isDeleting = ref(false);

const productsCount = computed(() => props.category?.products_count ?? 0);

const hasProducts = computed(() => productsCount.value > 0);

watch(
    () => props.open,
    (open) => {
        if (open) {
            isDeleting.value = false;
        }
    },
);

function close(): void {
    emit('update:open', false);
}

function destroy(): void {
    if (!props.category || hasProducts.value) {
        return;
    }

    isDeleting.value = true;

    router.delete(route('dashboard.categories.destroy', props.category.id), {
        preserveScroll: true,
        onSuccess: close,
        onError: () => {
            isDeleting.value = false;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}
</script>

<template>
    <AlertDialog :open="open" @update:open="emit('update:open', $event)">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>
                    Delete “{{ category?.name }}”?
                </AlertDialogTitle>
                <AlertDialogDescription>
                    <template v-if="hasProducts">
                        This category still holds {{ productsCount }}
                        product(s). Reassign them to another category first,
                        otherwise deleting it will remove them too.
                    </template>
                    <template v-else>
                        This cannot be undone. The category will be permanently
                        removed.
                    </template>
                </AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <Button
                    variant="destructive"
                    :disabled="isDeleting || hasProducts"
                    @click="destroy"
                >
                    {{ isDeleting ? 'Deleting...' : 'Delete category' }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
