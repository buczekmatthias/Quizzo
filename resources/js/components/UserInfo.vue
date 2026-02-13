<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';

type Props = {
    user: User;
    showUsername?: boolean;
};

withDefaults(defineProps<Props>(), {
    showUsername: false,
});

const { getInitials } = useInitials();
</script>

<template>
    <Button
        variant="outline"
        size="icon"
        class="relative size-8 cursor-pointer rounded-lg p-1 focus-within:ring-2 focus-within:ring-primary"
    >
        {{ getInitials(user.name) }}
    </Button>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">{{ user.name }}</span>
        <span
            v-if="showUsername"
            class="truncate text-xs text-muted-foreground"
            >{{ user.username }}</span
        >
    </div>
</template>
