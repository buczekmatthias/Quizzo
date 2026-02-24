<script setup lang="ts">
import { Deferred, Head, Link } from '@inertiajs/vue3';
import Category from '@/components/Category.vue';
import Heading from '@/components/Heading.vue';
import Quiz from '@/components/Quiz.vue';
import Skeleton from '@/components/Skeleton.vue';
import { Button } from '@/components/ui/button';
import { useCurrentUser } from '@/composables/useUser';
import AppLayout from '@/layouts/AppLayout.vue';
import { profile } from '@/routes';
import quizzes from '@/routes/quizzes';
import type { Category as CategoryType, Quiz as QuizType } from '@/types';

type Props = {
    favoriteCategories?: CategoryType[];
    favoriteCategoriesQuizzes?: QuizType[];
    selectedQuizzes?: QuizType[];
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
                        <template v-if="favoriteCategories.length > 0">
                            <div class="flex flex-wrap items-center gap-3">
                                <Category
                                    :category
                                    v-for="category in favoriteCategories"
                                    :key="category.slug"
                                />
                                <Button as-child>
                                    <Link
                                        :href="profile()"
                                        class="whitespace-nowrap"
                                    >
                                        Show all
                                    </Link>
                                </Button>
                            </div>
                        </template>
                        <p class="text-muted-foreground italic" v-else>
                            Nothing to show
                        </p>
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
                        <template v-if="favoriteCategoriesQuizzes.length > 0">
                            <Quiz
                                :quiz
                                v-for="quiz in favoriteCategoriesQuizzes"
                                :key="quiz.slug"
                            />

                            <Button as-child>
                                <Link
                                    :href="profile()"
                                    class="whitespace-nowrap"
                                >
                                    Show all
                                </Link>
                            </Button>
                        </template>
                        <p class="text-muted-foreground italic" v-else>
                            Nothing to show
                        </p>
                    </template>
                </Deferred>
            </div>
        </template>

        <div class="flex flex-col gap-2">
            <Heading variant="small" title="Quizzes" />

            <Deferred data="selectedQuizzes">
                <template #fallback>
                    <Skeleton />
                </template>

                <template v-if="selectedQuizzes">
                    <Quiz
                        :quiz
                        v-for="quiz in selectedQuizzes"
                        :key="quiz.slug"
                    />

                    <Button as-child>
                        <Link :href="quizzes.index()" class="whitespace-nowrap">
                            Show all
                        </Link>
                    </Button>
                </template>
            </Deferred>
        </div>
    </AppLayout>
</template>
