<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import { cn } from '@/lib/utils';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
    class?: HTMLAttributes['class'];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            :class="cn('flex w-full max-w-7xl flex-col gap-4 p-4', props.class)"
        >
            <slot />
        </div>
    </AppLayout>
</template>
