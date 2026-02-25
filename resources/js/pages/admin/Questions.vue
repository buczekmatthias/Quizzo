<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ExternalTextLink from '@/components/ExternalTextLink.vue';
import PaginatedContent from '@/components/PaginatedContent.vue';
import Table from '@/components/Table.vue';
import TextLink from '@/components/TextLink.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import quizzes from '@/routes/quizzes';
import type { Pagination, QuestionWithQuiz } from '@/types';
import { type BreadcrumbItem } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';

type Props = {
    questions: Pagination & { data: QuestionWithQuiz[] };
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Questions',
        href: admin.questions.index().url,
    },
];
const headers = [
    {
        content: 'Slug',
    },
    {
        content: 'Content',
    },
    {
        content: 'Image',
    },
    {
        content: 'Answers',
    },
    {
        content: 'Quiz',
    },
];
</script>

<template>
    <Head title="Questions" />

    <AdminLayout :breadcrumbs="breadcrumbs" class="max-w-fit">
        <PaginatedContent :pagination="questions" preserve-scroll>
            <Table :headers>
                <TableRow
                    v-for="question in questions.data"
                    :key="question.slug"
                >
                    <TableCell>
                        {{ question.slug }}
                    </TableCell>
                    <TableCell>
                        {{ question.content }}
                    </TableCell>
                    <TableCell>
                        <ExternalTextLink
                            v-if="question.has_image"
                            :href="question.image"
                        >
                            View file
                        </ExternalTextLink>
                    </TableCell>
                    <TableCell class="text-center">
                        {{ question.answers_count }}
                    </TableCell>
                    <TableCell>
                        <TextLink
                            :href="
                                quizzes.show({
                                    quiz: question.quiz.slug,
                                    token: question.quiz.token,
                                })
                            "
                        >
                            View
                        </TextLink>
                    </TableCell>
                </TableRow>
            </Table>
        </PaginatedContent>
    </AdminLayout>
</template>
