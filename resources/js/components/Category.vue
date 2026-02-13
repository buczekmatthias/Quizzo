<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import type { Category } from '@/types';

defineProps<{
    category: Category;
}>();
</script>

<template>
    <Button as-child variant="outline">
        <Link href="#">
            {{ category.name }}
        </Link>
    </Button>
</template>
