<script setup lang="ts">
type Props = {
    title: string;
    description?: string;
    variant?: 'default' | 'small';
    truncate?: boolean;
};

withDefaults(defineProps<Props>(), {
    variant: 'default',
    truncate: () => false,
});
</script>

<template>
    <header
        :class="{
            'mb-8 space-y-0.5': variant === 'default',
            truncate: truncate,
        }"
    >
        <h2
            :class="{
                'mb-0.5 text-base font-medium': variant === 'small',
                'text-xl font-semibold tracking-tight': variant === 'default',
                truncate: truncate,
            }"
        >
            {{ title }}
        </h2>
        <p
            v-if="description"
            class="text-sm text-muted-foreground"
            :class="{ truncate: truncate }"
        >
            {{ description }}
        </p>
    </header>
</template>
