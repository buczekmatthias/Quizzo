<script setup lang="ts">
import { Deferred, Head, useForm } from '@inertiajs/vue3';
import {
    getLocalTimeZone,
    today,
    type ZonedDateTime,
} from '@internationalized/date';
import { computed, provide, ref } from 'vue';
import ActionButton from '@/components/ActionButton.vue';
import CategoriesInput from '@/components/CategoriesInput.vue';
import CreateQuizQuestion from '@/components/CreateQuizQuestion.vue';
import DateTimePicker from '@/components/DateTimePicker.vue';
import FormBox from '@/components/FormBox.vue';
import FormGroup from '@/components/FormGroup.vue';
import Heading from '@/components/Heading.vue';
import Radio from '@/components/Radio.vue';
import Skeleton from '@/components/Skeleton.vue';
import Switch from '@/components/Switch.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import quizzes from '@/routes/quizzes';
import type { Category } from '@/types';
import { type BreadcrumbItem } from '@/types';

type Props = {
    categories?: Category[];
    max_answers_count: number;
};

export type NewQuestionAnswer = {
    content: string | null;
    is_correct_answer: boolean;
    is_content_file_type: boolean;
};

export type NewQuizQuestion = {
    content: string;
    image: any;
    answers: NewQuestionAnswer[];
};

const props = defineProps<Props>();

provide('max_answers_count', props.max_answers_count);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: quizzes.index().url,
    },
    {
        title: 'Create quiz',
        href: quizzes.create().url,
    },
];

const noTimeLimit = ref<boolean>(true);

const form = useForm({
    title: '',
    description: '',
    is_public: true,
    started_at: '',
    finished_at: '',
    categories: [] as Category[],
    questions: [] as NewQuizQuestion[],
});

const isValid = computed((): boolean => {
    return (
        form.title.length > 0 &&
        typeof form.started_at === 'object' &&
        form.questions.length > 1 &&
        form.questions.every((question: NewQuizQuestion) => {
            return (
                question.content.length > 0 &&
                question.answers.length > 0 &&
                question.answers.every(
                    (answer: NewQuestionAnswer) => answer.content,
                ) &&
                question.answers.some(
                    (answer: NewQuestionAnswer) =>
                        answer.is_correct_answer === true,
                )
            );
        })
    );
});

const submitForm = () => {
    if (!isValid.value) return;

    if (
        confirm(
            "Are you sure you want to create quiz in current state? You won't be able to update it.",
        )
    ) {
        form.transform((data) => ({
            ...data,
            started_at: (
                data.started_at as unknown as ZonedDateTime
            ).toAbsoluteString(),
            finished_at: noTimeLimit.value
                ? null
                : (
                      data.finished_at as unknown as ZonedDateTime
                  ).toAbsoluteString(),
            categories: data.categories.map(
                (category: Category) => category.slug,
            ),
        })).post(quizzes.store().url);
    }
};
</script>

<template>
    <Head title="Create quiz" />

    <AppLayout :breadcrumbs="breadcrumbs" class="gap-6">
        <Heading
            title="Create new quiz"
            description="Provide all necessary informations and set your quiz live!"
        />

        <FormBox required label="Title" id="title">
            <Input
                v-model="form.title"
                id="title"
                placeholder="Example quiz title"
            />
        </FormBox>

        <FormBox label="Description" id="description">
            <Input
                v-model="form.description"
                id="description"
                placeholder="Example quiz description"
            />
        </FormBox>

        <FormGroup label="Visibility" required>
            <Radio
                :options="[
                    {
                        label: 'Public',
                        value: true,
                    },
                    {
                        label: 'Private',
                        description: 'Access only with token',
                        value: false,
                    },
                ]"
                v-model="form.is_public"
            />
        </FormGroup>

        <FormGroup label="Start" required>
            <DateTimePicker
                v-model="form.started_at"
                :add-time="{ minutes: 15 }"
            />
        </FormGroup>

        <FormGroup label="End">
            <div class="flex flex-col gap-6">
                <Switch
                    label="Unlimited time"
                    id="unlimited_finish_time"
                    v-model="noTimeLimit"
                />

                <DateTimePicker
                    :min-value="today(getLocalTimeZone()).add({ days: 1 })"
                    v-model="form.finished_at"
                    v-if="!noTimeLimit"
                />
            </div>
        </FormGroup>

        <Deferred data="categories">
            <template #fallback>
                <Skeleton :count="1" />
            </template>

            <FormBox v-if="categories" label="Categories">
                <CategoriesInput :categories v-model="form.categories" />
            </FormBox>
        </Deferred>

        <Separator />

        <Heading :title="`Questions (${form.questions.length})`" />

        <CreateQuizQuestion
            v-for="(question, i) in form.questions"
            v-model="form.questions[i]"
            :key="i"
            :index="i"
            @remove-question="form.questions.splice($event, 1)"
        />

        <Button
            variant="outline"
            @click="
                form.questions.push({
                    content: '',
                    image: null,
                    answers: [],
                })
            "
        >
            Add new question
        </Button>

        <Separator />

        <ActionButton
            label="Save new quiz"
            @click="submitForm"
            :disabled="!isValid"
            :procession="form.processing"
        />
    </AppLayout>
</template>
