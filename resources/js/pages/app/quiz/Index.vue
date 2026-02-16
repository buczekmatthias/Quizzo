<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import PaginatedContent from '@/components/PaginatedContent.vue';
import Quiz from '@/components/Quiz.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import quizzes from '@/routes/quizzes';
import type { PaginatedQuiz } from '@/types';
import { type BreadcrumbItem } from '@/types';

type Props = {
    paginatedQuizzes: PaginatedQuiz;
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: quizzes.index().url,
    },
];
</script>

<template>
    <Head title="Quizzes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Quizzes"
            description="Explore quizzes and find ones that interest you the most!"
        />

        <PaginatedContent :pagination="paginatedQuizzes">
            <Quiz
                v-for="quiz in paginatedQuizzes.data"
                :key="quiz.slug"
                :quiz
            />
        </PaginatedContent>
    </AppLayout>
</template>
