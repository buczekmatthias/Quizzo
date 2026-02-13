<script setup lang="ts">
import { Calendar, CalendarOff, Lock } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import TextLink from '@/components/TextLink.vue';
import type { Quiz } from '@/types';

defineProps<{
    quiz: Quiz;
}>();
</script>

<template>
    <div class="flex flex-col gap-5 rounded-md border p-3">
        <div class="flex items-center gap-3">
            <Lock v-if="!quiz.is_public" class="shrink-0 max-md:size-5" />
            <Heading variant="small" :title="quiz.title" truncate />
        </div>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <Calendar class="max-md:size-5" />
                <p class="max-md:text-sm">{{ quiz.started_at }}</p>
            </div>
            <div class="flex items-center gap-2">
                <CalendarOff class="max-md:size-5" />
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
        <TextLink href="#" class="self-start"> Take a quiz </TextLink>
    </div>
</template>
