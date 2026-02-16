<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { capitalize } from 'vue';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

type Props = {
    label?: string;
    id?: string;
    class?: HTMLAttributes['class'];
    required?: boolean;
};

const props = defineProps<Props>();
</script>

<template>
    <div :class="cn('flex flex-col gap-1.5', props.class)">
        <Label :for="id" v-if="label">
            {{ capitalize(label) }}
            <span v-if="required" class="text-red-500">*</span>
        </Label>
        <slot />
    </div>
</template>
