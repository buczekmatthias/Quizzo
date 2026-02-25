<script setup lang="ts">
import { Calendar, CalendarOff, User } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import QuizAccessBadge from '@/components/QuizAccessBadge.vue';
import TextLink from '@/components/TextLink.vue';
import quizzes from '@/routes/quizzes';
import type { Quiz } from '@/types';

type Props = {
    quiz: Quiz;
};

defineProps<Props>();
</script>

<template>
    <div class="flex flex-col gap-5 rounded-md border p-3">
        <QuizAccessBadge v-if="!quiz.is_public" />
        <Heading variant="small" :title="quiz.title" truncate />
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <Calendar class="size-4 md:size-5" />
                <p class="max-md:text-sm">{{ quiz.started_at }}</p>
            </div>
            <div class="flex items-center gap-2">
                <CalendarOff class="size-4 md:size-5" />
                <p
                    class="max-md:text-sm"
                    :class="{
                        'text-muted-foreground italic': !quiz.finished_at,
                    }"
                >
                    {{ quiz.finished_at ?? 'Not proivded' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <User class="size-4 md:size-5" />
                <p class="max-md:text-sm">
                    {{ quiz.participants_count }}
                </p>
            </div>
        </div>
        <TextLink :href="quizzes.show({ quiz })" class="self-start">
            Take a quiz
        </TextLink>
    </div>
</template>
