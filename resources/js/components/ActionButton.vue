<script setup lang="ts">
import { LoaderCircle } from 'lucide-vue-next';
import { Button, type ButtonVariants } from '@/components/ui/button';

type Props = {
    isProcessing?: boolean;
    disabled?: boolean;
    variant?: ButtonVariants['variant'];
    label: string;
};

defineProps<Props>();
</script>

<template>
    <Button
        type="submit"
        class="w-full"
        :disabled="isProcessing || disabled"
        :variant
    >
        <LoaderCircle v-if="isProcessing" class="h-4 w-4 animate-spin" />
        {{ label }}
    </Button>
</template>
