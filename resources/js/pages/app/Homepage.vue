<script setup lang="ts">
import { Deferred, Head, Link } from '@inertiajs/vue3';
import Category from '@/components/Category.vue';
import Heading from '@/components/Heading.vue';
import Quiz from '@/components/Quiz.vue';
import Skeleton from '@/components/Skeleton.vue';
import { Button } from '@/components/ui/button';
import { useCurrentUser } from '@/composables/useUser';
import AppLayout from '@/layouts/AppLayout.vue';
import quizes from '@/routes/quizes';
import type { Category as CategoryType, Quiz as QuizType } from '@/types';

type Props = {
    favoriteCategories?: CategoryType[];
    favoriteCategoriesQuizzes?: QuizType[];
    quizzes?: QuizType[];
};

defineProps<Props>();
</script>

<template>
    <Head title="Homepage" />

    <AppLayout class="gap-8">
        <template v-if="useCurrentUser.user">
            <div class="flex flex-col gap-2">
                <Heading variant="small" title="Your favorite categories" />

                <Deferred data="favoriteCategories">
                    <template #fallback>
                        <Skeleton
                            container-class="flex-row"
                            skeleton-class="h-10 w-32"
                            :count="3"
                        />
                    </template>

                    <template v-if="favoriteCategories">
                        <div class="flex flex-wrap items-center gap-3">
                            <Category
                                :category
                                v-for="category in favoriteCategories"
                                :key="category.slug"
                            />
                            <Button as-child>
                                <Link href="#" class="whitespace-nowrap">
                                    Show all
                                </Link>
                            </Button>
                        </div>
                    </template>
                </Deferred>
            </div>

            <div class="flex flex-col gap-2">
                <Heading
                    variant="small"
                    title="Quizzes from your favorite categories"
                />

                <Deferred data="favoriteCategoriesQuizzes">
                    <template #fallback>
                        <Skeleton :count="5" />
                    </template>

                    <template v-if="favoriteCategoriesQuizzes">
                        <Quiz
                            :quiz
                            v-for="quiz in favoriteCategoriesQuizzes"
                            :key="quiz.slug"
                        />

                        <Button as-child>
                            <Link href="#" class="whitespace-nowrap">
                                Show all
                            </Link>
                        </Button>
                    </template>
                </Deferred>
            </div>
        </template>

        <div class="flex flex-col gap-2">
            <Heading variant="small" title="Quizzes" />

            <Deferred data="quizzes">
                <template #fallback>
                    <Skeleton />
                </template>

                <template v-if="quizzes">
                    <Quiz :quiz v-for="quiz in quizzes" :key="quiz.slug" />

                    <Button as-child>
                        <Link :href="quizes.index()" class="whitespace-nowrap">
                            Show all
                        </Link>
                    </Button>
                </template>
            </Deferred>
        </div>
    </AppLayout>
</template>
