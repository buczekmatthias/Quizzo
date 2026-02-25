<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import { type BreadcrumbItem } from '@/types';

type Props = {
    quizzes_count: {
        total: number;
        current_month: number;
        taken: number;
        taken_current_month: number;
    };
    users_count: {
        total: number;
        current_month: number;
    };
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: admin.dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <Heading title="Dashboard" />
        <div
            class="grid grid-cols-1 gap-4 rounded-md bg-accent/50 p-4 max-md:gap-8 md:grid-cols-2"
        >
            <Heading
                :title="`${quizzes_count.total} quizzes`"
                description="Total quizzes"
            />
            <Heading
                :title="`${quizzes_count.current_month} quizzes`"
                description="Total quizzes this month"
            />
            <Heading
                :title="`${quizzes_count.taken} quizzes`"
                description="Finished quizzes"
            />
            <Heading
                :title="`${quizzes_count.taken_current_month} quizzes`"
                description="Finished quizzes this month"
            />
            <Heading
                :title="`${users_count.total} users`"
                description="Total users"
            />
            <Heading
                :title="`${users_count.current_month} users`"
                description="Total users this month"
            />
        </div>
    </AdminLayout>
</template>
