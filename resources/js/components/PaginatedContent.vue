<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import type { Pagination as PaginationType } from '@/types';

withDefaults(
    defineProps<{
        pagination: PaginationType;
        pageName?: string;
        reloadOnly?: string[];
        paginationPosition?: 'top' | 'bottom';
        containerClass?: string;
        preserveScroll?: boolean;
    }>(),
    {
        pageName: () => 'page',
        reloadOnly: () => [],
        paginationPosition: () => 'bottom',
        containerClass: () => 'flex flex-col gap-4',
        preserveScroll: () => false,
    },
);
</script>

<template>
    <div :class="containerClass">
        <Pagination
            :page-name="pageName"
            :pagination="pagination"
            :reload-only="reloadOnly"
            :class="paginationPosition === 'top' ? 'order-first' : 'order-last'"
            :preserve-scroll
        />
        <slot />
    </div>
</template>
