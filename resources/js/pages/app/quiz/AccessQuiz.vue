<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ActionButton from '@/components/ActionButton.vue';
import FormBox from '@/components/FormBox.vue';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import quizzes from '@/routes/quizzes';
import type { Quiz } from '@/types';
import { type BreadcrumbItem } from '@/types';

type Props = {
    quiz: Quiz;
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: quizzes.index().url,
    },
    {
        title: 'Access quiz',
        href: quizzes.show({ quiz: props.quiz }).url,
    },
];

const token = ref<string>('');
</script>

<template>
    <Head title="Quizzes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading :title="quiz.title" :description="quiz.description" />

        <FormBox label="Access token" id="access_token" required>
            <Input v-model="token" id="access_token" />
        </FormBox>

        <ActionButton
            :disabled="token.length === 0"
            label="Access the quiz"
            @click="router.get(quizzes.show({ quiz, token }))"
        />
    </AppLayout>
</template>
