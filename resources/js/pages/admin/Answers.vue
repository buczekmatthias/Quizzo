<script setup lang="ts">
import { Head, InfiniteScroll, router } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import ExternalTextLink from '@/components/ExternalTextLink.vue';
import Switch from '@/components/Switch.vue';
import Table from '@/components/Table.vue';
import TextLink from '@/components/TextLink.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import quizzes from '@/routes/quizzes';
import type { AnswerWithQuiz, Pagination } from '@/types';
import { type BreadcrumbItem } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';

type Props = {
    answers: Pagination & { data: AnswerWithQuiz[] };
    filters: {
        correct_only: boolean;
        files_only: boolean;
    };
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Answers',
        href: admin.answers.index().url,
    },
];

const onlyCorrectAnswers = ref<boolean>(props.filters.correct_only);
const onlyFileAnswers = ref<boolean>(props.filters.files_only);

const headers = [
    {
        content: 'Slug',
    },
    {
        content: 'Content',
    },
    {
        content: 'File type',
    },
    {
        content: 'Correct answer',
    },
    {
        content: 'Quiz',
    },
];

watch(
    () => onlyCorrectAnswers.value,
    () => {
        router.reload({
            data: { onlyCorrectAnswers: onlyCorrectAnswers.value },
            only: ['answers'],
            reset: ['answers'],
        });
    },
);

watch(
    () => onlyFileAnswers.value,
    () => {
        router.reload({
            data: { onlyFileAnswers: onlyFileAnswers.value },
            only: ['answers'],
            reset: ['answers'],
        });
    },
);
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-wrap gap-6">
            <Switch
                id="correct_only"
                v-model="onlyCorrectAnswers"
                label="Show only correct answers"
            />
            <Switch
                id="file_onle"
                v-model="onlyFileAnswers"
                label="Show answers with file content"
            />
        </div>
        <InfiniteScroll data="answers" preserve-url>
            <Table :headers>
                <TableRow v-for="answer in answers.data" :key="answer.slug">
                    <TableCell>
                        {{ answer.slug }}
                    </TableCell>
                    <TableCell>
                        <template v-if="!answer.is_content_file_type">
                            {{ answer.content }}
                        </template>
                        <ExternalTextLink v-else :href="answer.content">
                            View file
                        </ExternalTextLink>
                    </TableCell>
                    <TableCell>
                        <Check
                            v-if="answer.is_content_file_type"
                            class="mx-auto"
                        />
                    </TableCell>
                    <TableCell>
                        <Check
                            v-if="answer.is_correct_answer"
                            class="mx-auto"
                        />
                    </TableCell>
                    <TableCell>
                        <TextLink
                            :href="
                                quizzes.show({
                                    quiz: answer.quiz.slug,
                                    token: answer.quiz.token,
                                })
                            "
                        >
                            View
                        </TextLink>
                    </TableCell>
                </TableRow>
            </Table>
            <template #loading>
                <TableRow>
                    <TableCell>Loading more answers...</TableCell>
                </TableRow>
            </template>
        </InfiniteScroll>
    </AdminLayout>
</template>
