<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import type { Answer, Pagination } from '@/types';
import { type BreadcrumbItem } from '@/types';

type Props = {
    answers?: Pagination & { data: Answer[] };
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Answers',
        href: admin.answers.index().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <p>{{ answers }}</p>
    </AdminLayout>
</template>
