<script setup lang="ts">
type Props = {
    href: HTMLAnchorElement['href'];
};

defineProps<Props>();
</script>

<template>
    <a
        :href="href"
        target="_blank"
        class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
    >
        <slot />
    </a>
</template>
