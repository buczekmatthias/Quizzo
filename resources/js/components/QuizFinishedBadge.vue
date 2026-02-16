<script setup lang="ts">
import { Check } from 'lucide-vue-next';

type Props = {
    label?: string;
};

withDefaults(defineProps<Props>(), {
    label: () => 'Quiz finished',
});
</script>

<template>
    <div
        class="flex items-center gap-2 self-start rounded-md bg-emerald-700/10 p-2 text-emerald-600 dark:bg-emerald-700/10 dark:text-emerald-400"
    >
        <Check class="size-4 shrink-0 md:size-5" />
        <p class="text-sm">{{ label }}</p>
    </div>
</template>
