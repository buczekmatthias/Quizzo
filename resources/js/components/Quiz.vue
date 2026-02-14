<script setup lang="ts">
import { Calendar, CalendarOff, Lock } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import TextLink from '@/components/TextLink.vue';
import type { Quiz } from '@/types';

type Props = {
    quiz: Quiz;
};

defineProps<Props>();
</script>

<template>
    <div class="flex flex-col gap-5 rounded-md border p-3">
        <div
            class="flex items-center gap-2 self-start rounded-md bg-red-700/10 p-2 text-red-600 dark:bg-sky-700/10 dark:text-sky-400"
            v-if="!quiz.is_public"
        >
            <Lock class="size-4 shrink-0 md:size-5" />
            <p class="text-sm">Access token required</p>
        </div>
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
        </div>
        <TextLink href="#" class="self-start">Take a quiz</TextLink>
    </div>
</template>
