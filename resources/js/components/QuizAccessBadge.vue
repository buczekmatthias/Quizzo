<script setup lang="ts">
import { Lock } from 'lucide-vue-next';
</script>

<template>
    <div
        class="flex items-center gap-2 self-start rounded-md bg-red-700/10 p-2 text-red-600 dark:bg-sky-700/10 dark:text-sky-400"
    >
        <Lock class="size-4 shrink-0 md:size-5" />
        <p class="text-sm">Access token required</p>
    </div>
</template>
