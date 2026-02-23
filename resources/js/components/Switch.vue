<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';

type Props = {
    label: string;
    id: string;
};

defineProps<Props>();

const model = defineModel<boolean>();
</script>

<template>
    <div class="flex items-center space-x-3 *:cursor-pointer">
        <Switch v-model="model" :id />
        <Label :for="id">{{ label }}</Label>
    </div>
</template>
