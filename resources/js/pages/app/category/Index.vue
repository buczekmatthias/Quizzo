<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Star } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import PaginatedContent from '@/components/PaginatedContent.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import categories from '@/routes/categories';
import type {
    BreadcrumbItem,
    Category as CategoryType,
    Pagination,
} from '@/types';

type Props = {
    paginatedCategories: Pagination & { data: CategoryType[] };
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: categories.index().url,
    },
];
</script>

<template>
    <Head title="Homepage" />

    <AppLayout class="gap-8" :breadcrumbs>
        <PaginatedContent :pagination="paginatedCategories">
            <Link
                :href="categories.show(category)"
                class="flex items-center justify-between gap-3 rounded-md border p-3 duration-150 hover:bg-accent/30"
                v-for="category in paginatedCategories.data"
                :key="category.slug"
            >
                <Heading
                    variant="small"
                    :title="category.name"
                    :description="`${category.quizzes_count} quizzes`"
                />
                <Star v-if="category.is_favorite" />
            </Link>
        </PaginatedContent>
    </AppLayout>
</template>
