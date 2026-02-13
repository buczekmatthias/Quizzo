<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
import { cn } from '@/lib/utils';

withDefaults(
    defineProps<{
        count?: number;
        containerClass?: string;
        skeletonClass?: string;
    }>(),
    {
        count: () => 8,
    },
);
</script>

<template>
    <div :class="cn('flex flex-col gap-4', containerClass)">
        <Skeleton
            :class="cn('h-18 w-full rounded-md', skeletonClass)"
            v-for="i in count"
            :key="i"
        />
    </div>
</template>
