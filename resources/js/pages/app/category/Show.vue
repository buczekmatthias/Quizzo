<script setup lang="ts">
import { Deferred, Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Star } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import PaginatedContent from '@/components/PaginatedContent.vue';
import Quiz from '@/components/Quiz.vue';
import Skeleton from '@/components/Skeleton.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import categories from '@/routes/categories';
import type {
    BreadcrumbItem,
    Category as CategoryType,
    PaginatedQuiz,
} from '@/types';

type Props = {
    category: CategoryType;
    quizzes?: PaginatedQuiz;
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: categories.index().url,
    },
    {
        title: 'View category',
        href: categories.show(props.category).url,
    },
];
</script>

<template>
    <Head title="Homepage" />

    <AppLayout class="gap-6" :breadcrumbs>
        <Button variant="outline" as-child class="self-start">
            <Link :href="categories.index()">
                <ArrowLeft />
                <span>Categories list</span>
            </Link>
        </Button>
        <div class="flex items-center justify-between gap-3">
            <Heading
                variant="small"
                :title="category.name"
                :description="`${category.quizzes_count} quizzes`"
            />
            <Button
                :variant="category.is_favorite ? 'default' : 'outline'"
                as-child
            >
                <Link
                    :href="categories.favorite(category)"
                    method="patch"
                    :only="['category']"
                >
                    <Star />
                    <span v-if="category.is_favorite">You like it</span>
                </Link>
            </Button>
        </div>

        <Separator />

        <Deferred data="quizzes">
            <template #fallback>
                <Skeleton />
            </template>

            <PaginatedContent :pagination="quizzes" v-if="quizzes">
                <template v-if="quizzes.data.length > 0">
                    <Quiz v-for="quiz in quizzes.data" :key="quiz.slug" :quiz />
                </template>
                <p v-else>No quizzes to display</p>
            </PaginatedContent>
        </Deferred>
    </AppLayout>
</template>
