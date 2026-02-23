<script setup lang="ts">
type Props = {
    label: string;
    required?: boolean;
};

defineProps<Props>();
</script>

<template>
    <div class="relative rounded-md border p-4 pt-6">
        <p class="absolute -top-2.5 left-2 bg-background px-2 text-sm">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </p>
        <slot />
    </div>
</template>
