<script setup lang="ts">
import { InfiniteScroll, Head } from '@inertiajs/vue3';
import ActionButton from '@/components/ActionButton.vue';
import Category from '@/components/Category.vue';
import Heading from '@/components/Heading.vue';
import Quiz from '@/components/Quiz.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { profile } from '@/routes';
import type {
    BreadcrumbItem,
    Category as CategoryType,
    Pagination,
    Quiz as QuizType,
} from '@/types';

type Props = {
    favoriteCategories: Pagination & { data: CategoryType[] };
    favoriteCategoriesQuizzes: Pagination & { data: QuizType[] };
    finishedQuizzes: Pagination & { data: QuizType[] };
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile',
        href: profile().url,
    },
];
</script>

<template>
    <Head title="Homepage" />

    <AppLayout class="gap-6" :breadcrumbs>
        <Heading
            title="Your profile"
            description="You can find all your favorite categories, quizzes from those categories and quizzes you took."
        />

        <div class="flex flex-col gap-2">
            <Heading variant="small" title="Your favorite categories" />

            <InfiniteScroll data="favoriteCategories" manual>
                <template v-if="favoriteCategories.data.length > 0">
                    <div class="flex flex-wrap items-center gap-3">
                        <Category
                            :category
                            v-for="category in favoriteCategories.data"
                            :key="category.slug"
                        />
                    </div>
                </template>
                <p class="text-muted-foreground italic" v-else>
                    Nothing to show
                </p>

                <template #next="{ loading, fetch, hasMore }">
                    <ActionButton
                        v-if="hasMore"
                        @click="fetch"
                        :disabled="loading"
                        :label="loading ? 'Loading...' : 'Load more'"
                        :is-processing="loading"
                    />
                </template>
            </InfiniteScroll>
        </div>

        <div class="flex flex-col gap-2">
            <Heading
                variant="small"
                title="Quizzes from your favorite categories"
            />

            <InfiniteScroll data="favoriteCategoriesQuizzes" manual>
                <template v-if="favoriteCategoriesQuizzes.data.length > 0">
                    <div class="flex flex-col gap-4">
                        <Quiz
                            :quiz
                            v-for="quiz in favoriteCategoriesQuizzes.data"
                            :key="quiz.slug"
                        />
                    </div>
                </template>
                <p class="text-muted-foreground italic" v-else>
                    Nothing to show
                </p>

                <template #next="{ loading, fetch, hasMore }">
                    <ActionButton
                        v-if="hasMore"
                        @click="fetch"
                        :is-processing="loading"
                        class="w-full"
                        :label="loading ? 'Loading...' : 'Load more'"
                    />
                </template>
            </InfiniteScroll>
        </div>
        <div class="flex flex-col gap-2">
            <Heading variant="small" title="Quizzes you finished" />

            <InfiniteScroll data="finishedQuizzes" manual>
                <template v-if="finishedQuizzes.data.length > 0">
                    <div class="flex flex-col gap-4">
                        <Quiz
                            :quiz
                            v-for="quiz in finishedQuizzes.data"
                            :key="quiz.slug"
                        />
                    </div>
                </template>
                <p class="text-muted-foreground italic" v-else>
                    Nothing to show
                </p>

                <template #next="{ loading, fetch, hasMore }">
                    <ActionButton
                        v-if="hasMore"
                        @click="fetch"
                        class="w-full"
                        :is-processing="loading"
                        :label="loading ? 'Loading...' : 'Load more'"
                    />
                </template>
            </InfiniteScroll>
        </div>
    </AppLayout>
</template>
