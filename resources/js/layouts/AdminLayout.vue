<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
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
            :class="
                cn(
                    'flex w-full max-w-7xl flex-col gap-4 p-4 max-md:p-6',
                    props.class,
                )
            "
        >
            <slot />
        </div>
    </AppLayout>
</template>
