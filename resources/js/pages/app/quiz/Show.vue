<script setup lang="ts">
import { Deferred, Head, Link, useForm } from '@inertiajs/vue3';
import { Calendar, CalendarOff, Percent } from 'lucide-vue-next';
import { computed, watch } from 'vue';
import ActionButton from '@/components/ActionButton.vue';
import Category from '@/components/Category.vue';
import Heading from '@/components/Heading.vue';
import QuizAccessBadge from '@/components/QuizAccessBadge.vue';
import QuizFinishedBadge from '@/components/QuizFinishedBadge.vue';
import QuizQuestion from '@/components/QuizQuestion.vue';
import Skeleton from '@/components/Skeleton.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import quizzes from '@/routes/quizzes';
import type {
    Answer,
    Permissions,
    Question,
    Quiz,
    QuizFormQuestion,
} from '@/types';
import { type BreadcrumbItem } from '@/types';

type Props = {
    quiz: Quiz;
    questions?: Question[];
    permissions: Permissions;
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: quizzes.index().url,
    },
    {
        title: 'View quiz',
        href: quizzes.show({ quiz: props.quiz }).url,
    },
];

const form = useForm({
    questions: [] as QuizFormQuestion[],
});

const submitForm = () => {
    if (
        props.quiz.can_be_done &&
        confirm(
            "Are you sure you want to submit quiz? You won't be able to change your answers.",
        )
    ) {
        form.post(
            quizzes.submit({
                quiz: props.quiz.slug,
                token: props.quiz.token,
            }).url,
        );
    }
};

const quizScore = computed((): number | null => {
    if (!props.questions || props.quiz.can_be_done) return null;

    let score = 0;

    props.questions.forEach((question: Question) => {
        if (
            question.answers.some(
                (answer: Answer) =>
                    answer.is_correct_answer &&
                    answer.has_user_select_this_answer,
            )
        ) {
            score++;
        }
    });

    return score;
});

const scoreString = computed((): string => {
    const percent = (quizScore.value! / props.questions!.length) * 100;

    return `${quizScore.value}/${props.questions?.length} - ${percent}%`;
});

watch(
    () => props.questions,
    () => {
        if (props.questions) {
            const formQuestions: QuizFormQuestion[] = [];

            props.questions.forEach((question: Question) => {
                formQuestions.push({
                    slug: question.slug,
                    answer_selected_slug: '',
                });
            });

            form.questions = formQuestions;
        }
    },
);
</script>

<template>
    <Head :title="quiz.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex flex-wrap gap-4 self-start"
            v-if="!quiz.is_public || !quiz.can_be_done"
        >
            <QuizAccessBadge v-if="!quiz.is_public" />
            <QuizFinishedBadge
                v-if="!quiz.can_be_done"
                :label="quiz.did_user_do ? 'Quiz done' : 'Quiz ended'"
            />
        </div>

        <div class="flex flex-wrap gap-2" v-if="quiz.categories.length > 0">
            <Category
                :category
                v-for="category in quiz.categories"
                :key="category.slug"
            />
        </div>

        <Heading :title="quiz.title" :description="quiz.description" />

        <div class="flex flex-wrap items-center gap-6">
            <div class="flex gap-2">
                <Calendar class="mt-1 size-4 md:size-5" />
                <Heading
                    variant="small"
                    title="Started at"
                    :description="quiz.started_at"
                />
            </div>
            <div class="flex gap-2">
                <CalendarOff class="mt-1 size-4 md:size-5" />
                <Heading
                    variant="small"
                    title="Finished at"
                    :description="quiz.finished_at ?? 'Not proivded'"
                />
            </div>
            <div class="flex gap-2" v-if="quizScore">
                <Percent class="mt-1 size-4 md:size-5" />
                <Heading
                    variant="small"
                    title="Score"
                    :description="scoreString"
                />
            </div>

            <Button as-child v-if="permissions.finish && !quiz.has_finished">
                <Link
                    :href="
                        quizzes.update({ quiz: quiz.slug, token: quiz.token })
                    "
                    method="patch"
                >
                    Finish quiz now
                </Link>
            </Button>
            <Button
                as-child
                v-if="permissions.delete && !quiz.deleted_at"
                variant="destructive"
            >
                <Link :href="admin.quizzes.destroy(quiz)" method="delete">
                    Soft delete
                </Link>
            </Button>
            <Button as-child v-if="permissions.restore && quiz.deleted_at">
                <Link :href="admin.quizzes.destroy(quiz)" method="delete">
                    Restore
                </Link>
            </Button>
            <Button
                as-child
                v-if="permissions.forceDelete"
                variant="destructive"
            >
                <Link :href="admin.quizzes.destroy(quiz)" method="delete">
                    Force delete
                </Link>
            </Button>
        </div>

        <Separator />

        <Deferred data="questions">
            <template #fallback>
                <Skeleton :count="4" skeleton-class="h-32" />
            </template>

            <div
                class="flex flex-col gap-6"
                v-if="questions && form.questions.length > 0"
            >
                <template
                    v-for="(question, i) in questions"
                    :key="question.slug"
                >
                    <QuizQuestion
                        :question
                        :disabled="!quiz.can_be_done"
                        :index="i"
                        v-model="form.questions[i].answer_selected_slug"
                    />
                    <Separator v-if="i !== questions!.length - 1" />
                </template>
            </div>
        </Deferred>

        <ActionButton
            label="Submit quiz"
            v-if="quiz.can_be_done"
            @click="submitForm"
        />
    </AppLayout>
</template>
