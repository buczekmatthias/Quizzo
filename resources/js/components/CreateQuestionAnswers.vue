<script setup lang="ts">
import { inject, onMounted, ref } from 'vue';
import FormBox from '@/components/FormBox.vue';
import Switch from '@/components/Switch.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { NewQuestionAnswer } from '@/pages/app/quiz/Create.vue';

const model = defineModel<NewQuestionAnswer[]>();

const max_answers_count = inject<number | undefined>('max_answers_count');

const emptyAnswer = ref<NewQuestionAnswer>();

onMounted(() => {
    emptyAnswer.value = model.value?.[0];
});

const toggleSelected = (index: number) => {
    if (model.value) {
        const activeIndex = model.value.findIndex(
            (answer: NewQuestionAnswer) => answer.is_correct_answer === true,
        );

        model.value.map(
            (answer: NewQuestionAnswer) => (answer.is_correct_answer = false),
        );

        if (index !== activeIndex) {
            model.value[index].is_correct_answer = true;
        }
    }
};
</script>

<template>
    <div v-for="(answer, i) in model" :key="i" class="flex flex-col gap-2">
        <FormBox required label="Content">
            <Input
                type="file"
                @change="answer.content = $event.target.files[0]"
                accept="image/png,image/jpeg"
                v-if="answer.is_content_file_type"
            />
            <Input v-else v-model="answer.content as string" />
        </FormBox>
        <Switch
            label="Correct answer"
            :id="`is_${i}_correct_answer`"
            :model-value="answer.is_correct_answer"
            @click.prevent="toggleSelected(i)"
        />
    </div>

    <Button
        variant="outline"
        class="col-span-full"
        v-if="model!.length < max_answers_count!"
        @click="model!.push(emptyAnswer!)"
    >
        Add another answer
    </Button>
</template>
