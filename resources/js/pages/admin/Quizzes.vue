<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import PaginatedContent from '@/components/PaginatedContent.vue';
import Table from '@/components/Table.vue';
import TableActions from '@/components/TableActions.vue';
import TextLink from '@/components/TextLink.vue';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import quizzes from '@/routes/quizzes';
import type { Pagination, Permissions, Quiz } from '@/types';
import { type BreadcrumbItem } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';

type Props = {
    allQuizzes: Pagination & { data: Quiz[] };
    permissions: Permissions;
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: admin.quizzes.index().url,
    },
];

const headers = [
    {
        content: 'Title',
    },
    {
        content: 'Private',
    },
    {
        content: 'Creator',
    },
    {
        content: 'Start',
    },
    {
        content: 'End',
    },
    {
        content: 'Created',
    },
    {
        content: 'Deleted',
    },
    {
        content: 'Participants',
    },
    {
        content: 'Questions',
    },
    {
        content: 'Categories',
    },
];
</script>

<template>
    <Head title="Quizzes" />

    <AdminLayout :breadcrumbs="breadcrumbs" class="max-w-fit">
        <PaginatedContent :pagination="allQuizzes" preserve-scroll>
            <Table :headers>
                <TableRow v-for="quiz in allQuizzes.data" :key="quiz.slug">
                    <TableCell>
                        {{ quiz.title }}
                    </TableCell>
                    <TableCell>
                        <Check v-if="!quiz.is_public" class="mx-auto" />
                    </TableCell>
                    <TableCell>
                        <TextLink href="#">
                            {{ quiz.creator.name }} (@{{
                                quiz.creator.username
                            }})
                        </TextLink>
                    </TableCell>
                    <TableCell>
                        {{ quiz.started_at }}
                    </TableCell>
                    <TableCell>
                        <template v-if="quiz.finished_at">
                            {{ quiz.finished_at }}
                        </template>
                        <span class="text-muted-foreground" v-else> --- </span>
                    </TableCell>
                    <TableCell>
                        {{ quiz.created_at }}
                    </TableCell>
                    <TableCell>
                        <template v-if="quiz.deleted_at">
                            {{ quiz.deleted_at }}
                        </template>
                        <span class="text-muted-foreground" v-else> --- </span>
                    </TableCell>
                    <TableCell class="text-center">
                        {{ quiz.participants_count }}
                    </TableCell>
                    <TableCell class="text-center">
                        {{ quiz.questions_count }}
                    </TableCell>
                    <TableCell class="text-center">
                        {{ quiz.categories_count }}
                    </TableCell>
                    <TableActions>
                        <DropdownMenuItem
                            as-child
                            class="w-full cursor-pointer"
                        >
                            <Link
                                :href="
                                    quizzes.show({
                                        quiz: quiz.slug,
                                        token: quiz.token,
                                    })
                                "
                            >
                                View quiz
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            as-child
                            class="w-full cursor-pointer"
                            v-if="!quiz.has_finished"
                        >
                            <Link
                                :href="
                                    quizzes.update({
                                        quiz: quiz.slug,
                                        token: quiz.token,
                                    })
                                "
                                method="patch"
                            >
                                Finish quiz now
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            as-child
                            class="w-full cursor-pointer"
                            v-if="!quiz.deleted_at"
                        >
                            <Link
                                :href="admin.quizzes.destroy(quiz)"
                                method="delete"
                            >
                                Soft delete
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            as-child
                            class="w-full cursor-pointer"
                            v-if="permissions.restore && quiz.deleted_at"
                        >
                            <Link
                                :href="admin.quizzes.destroy(quiz)"
                                :data="{ action: 'restore' }"
                                method="delete"
                            >
                                Restore
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            as-child
                            class="w-full cursor-pointer"
                            v-if="permissions.forceDelete"
                        >
                            <Link
                                :href="admin.quizzes.destroy(quiz)"
                                :data="{ action: 'forceDelete' }"
                                method="delete"
                            >
                                Force delete
                            </Link>
                        </DropdownMenuItem>
                    </TableActions>
                </TableRow>
            </Table>
        </PaginatedContent>
    </AdminLayout>
</template>
