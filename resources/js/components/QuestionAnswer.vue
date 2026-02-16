<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { Answer } from '@/types';

type Props = {
    answer: Answer;
    disabled: boolean;
};

const props = defineProps<Props>();

const model = defineModel<string>();

const selectAnswer = () => {
    if (props.disabled) return;

    model.value = props.answer.slug;
};
</script>

<template>
    <div
        :class="
            cn(
                'flex cursor-pointer flex-col items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium whitespace-nowrap shadow-xs hover:bg-accent hover:text-accent-foreground dark:hover:bg-input/50',
                {
                    'pointer-events-none opacity-50': disabled,
                    'bg-background dark:border-input dark:bg-input/30':
                        !answer.is_correct_answer ||
                        !answer.has_user_select_this_answer,
                    'opacity-90':
                        answer.is_correct_answer ||
                        answer.has_user_select_this_answer,
                    'border-sky-400! bg-sky-400/5!':
                        answer.has_user_select_this_answer ||
                        model === answer.slug,
                    'border-emerald-400! bg-emerald-400/5!':
                        disabled && answer.is_correct_answer,
                },
            )
        "
        @click="selectAnswer"
    >
        <p v-if="!answer.is_content_file_type">
            {{ answer.content }}
        </p>
        <img :src="answer.content" alt="" v-else />

        <template v-if="disabled">
            <p v-if="answer.has_user_select_this_answer" class="text-sky-400">
                Your choice
            </p>
            <p v-if="answer.is_correct_answer" class="text-emerald-400">
                Correct choice
            </p>
        </template>
    </div>
</template>
