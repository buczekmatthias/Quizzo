<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import categories from '@/routes/categories';
import type { Category } from '@/types';

type Props = {
    category: Category;
};

defineProps<Props>();
</script>

<template>
    <Button as-child variant="outline">
        <Link :href="categories.show(category)">
            {{ category.name }}
        </Link>
    </Button>
</template>
