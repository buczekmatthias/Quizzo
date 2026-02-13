<script setup lang="ts">
import { useAppName } from '@/composables/useProps';
</script>

<template>
    <span class="mb-0.5 truncate leading-tight font-semibold">
        {{ useAppName }}
    </span>
</template>
