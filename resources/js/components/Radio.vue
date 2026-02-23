<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type Option = {
    label: string;
    description?: string;
    value: any;
};

type Props = {
    options: Option[];
    containerClass?: HTMLAttributes['class'];
};

defineProps<Props>();

const model = defineModel<any>();
</script>

<template>
    <div :class="cn('grid gap-3 md:grid-cols-2', containerClass)">
        <div
            :class="
                cn(
                    'flex cursor-pointer flex-col items-center justify-center gap-2 rounded-md border-2 bg-input/10 p-4',
                    {
                        'duration-150 hover:bg-input/30':
                            model !== option.value,
                        'pointer-events-none border-emerald-500 bg-emerald-500/5':
                            model === option.value,
                    },
                )
            "
            v-for="option in options"
            :key="option.value"
            @click="model = option.value"
        >
            <p class="font-semibold">{{ option.label }}</p>
            <p class="text-sm text-muted-foreground" v-if="option.description">
                {{ option.description }}
            </p>
        </div>
    </div>
</template>
