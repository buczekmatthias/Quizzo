<script setup lang="ts">
import QuestionAnswer from '@/components/QuestionAnswer.vue';
import type { Question } from '@/types';

type Props = {
    question: Question;
    disabled: boolean;
    index: number;
};

defineProps<Props>();

const model = defineModel<string>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <p>{{ index + 1 }}. {{ question.content }}</p>
        <img
            :src="question.image"
            alt="Question image"
            v-if="question.has_image"
        />
        <div class="grid gap-3 md:grid-cols-2">
            <QuestionAnswer
                class=""
                v-for="answer in question.answers"
                :key="answer.slug"
                :answer
                :disabled
                v-model="model"
            />
        </div>
    </div>
</template>
