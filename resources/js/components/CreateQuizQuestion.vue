<script setup lang="ts">
import { Trash } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import FormBox from '@/components/FormBox.vue';
import FormGroup from '@/components/FormGroup.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { NewQuizQuestion } from '@/pages/app/quiz/Create.vue';
import CreateQuestionAnswers from './CreateQuestionAnswers.vue';

type Props = {
    index: number;
};

defineProps<Props>();

const model = defineModel<NewQuizQuestion>();

const hasTextAnswers = ref<boolean | null>(null);

const emit = defineEmits(['removeQuestion']);

watch(hasTextAnswers, () => {
    if (hasTextAnswers.value) {
        model.value!.answers.push(
            {
                content: '',
                is_correct_answer: false,
                is_content_file_type: false,
            },
            {
                content: '',
                is_correct_answer: false,
                is_content_file_type: false,
            },
        );
    } else {
        model.value!.answers.push(
            {
                content: null,
                is_correct_answer: false,
                is_content_file_type: true,
            },
            {
                content: null,
                is_correct_answer: false,
                is_content_file_type: true,
            },
        );
    }
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between gap-2">
            <Heading :title="`Question #${index + 1}`" class="mb-0!" />
            <Button
                variant="destructive"
                @click="emit('removeQuestion', index)"
            >
                <Trash class="size-4" />
            </Button>
        </div>

        <FormBox required label="Content" :id="`question_${index}_content`">
            <Input
                v-model="model!.content"
                placeholder="Question content..."
                :id="`question_${index}_content`"
            />
        </FormBox>

        <FormBox label="Question image" :id="`question_${index}_image`">
            <Input
                type="file"
                :id="`question_${index}_image`"
                @change="model!.image = $event.target.files[0]"
                accept="image/png,image/jpeg"
            />
        </FormBox>

        <FormGroup label="Answers" required>
            <div class="grid grid-cols-2 gap-2" v-if="hasTextAnswers === null">
                <Button variant="outline" @click="hasTextAnswers = true">
                    Text
                </Button>
                <Button variant="outline" @click="hasTextAnswers = false">
                    Images
                </Button>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2" v-else>
                <CreateQuestionAnswers v-model="model!.answers" />
            </div>
        </FormGroup>
    </div>
</template>
