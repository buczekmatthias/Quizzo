<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import type { Pagination } from '@/types';

const props = withDefaults(
    defineProps<{
        pagination: Pagination;
        pageName?: string;
        reloadOnly?: string[];
    }>(),
    {
        pageName: () => 'page',
        reloadOnly: () => [],
    },
);

const rangeString = computed(
    () => `${props.pagination.meta.from} - ${props.pagination.meta.to > props.pagination.meta.total ? props.pagination.meta.total : props.pagination.meta.to} of
            ${props.pagination.meta.total} entries - Page: ${props.pagination.meta.current_page} of ${props.pagination.meta.last_page}`,
);
</script>

<template>
    <div
        class="flex items-center justify-between gap-2"
        v-if="pagination.meta.total > 0"
    >
        <p class="text-sm">{{ rangeString }}</p>
        <template v-if="pagination.meta.last_page > 1">
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    as-child
                    :disabled="!pagination.links.prev"
                >
                    <Link :href="pagination.links.prev || ''" as="button">
                        <ChevronLeft />
                    </Link>
                </Button>
                <Button
                    as-child
                    variant="outline"
                    :disabled="!pagination.links.next"
                >
                    <Link :href="pagination.links.next || ''" as="button">
                        <ChevronRight />
                    </Link>
                </Button>
            </div>
        </template>
    </div>
</template>
