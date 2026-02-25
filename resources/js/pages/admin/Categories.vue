<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CategoryTableRow from '@/components/CategoryTableRow.vue';
import FormBox from '@/components/FormBox.vue';
import PaginatedContent from '@/components/PaginatedContent.vue';
import Table from '@/components/Table.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import type { Category, Pagination } from '@/types';
import { type BreadcrumbItem } from '@/types';

type Props = {
    categories: Pagination & { data: Category[] };
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: admin.categories.index().url,
    },
];

const headers = [
    {
        content: 'Slug',
    },
    {
        content: 'Name',
    },
    {
        content: 'Quizzes',
    },
    {
        content: 'Users',
    },
];

const createFormOpen = ref<boolean>(false);
const createForm = useForm({
    name: '',
});

const submitCreateForm = () => {
    createForm.post(admin.categories.store().url, {
        only: ['categories'],
        onSuccess: () => {
            createForm.reset();
            createFormOpen.value = false;
        },
    });
};
</script>

<template>
    <Head title="Categories" />

    <AdminLayout :breadcrumbs="breadcrumbs" class="mx-auto max-w-5xl">
        <Dialog v-model:open="createFormOpen">
            <DialogTrigger as-child>
                <Button variant="outline" class="md:self-end">
                    Create categpry
                </Button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-105">
                <DialogHeader>
                    <DialogTitle>New category</DialogTitle>
                    <DialogDescription>
                        Add new category for quizzes
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4">
                    <FormBox required label="Name" id="name">
                        <Input
                            id="name"
                            name="name"
                            placeholder="Example category name..."
                            v-model="createForm.name"
                        />
                    </FormBox>
                </div>
                <Button type="submit" @click="submitCreateForm">
                    Create changes
                </Button>
            </DialogContent>
        </Dialog>

        <PaginatedContent :pagination="categories" preserve-scroll>
            <Table :headers>
                <CategoryTableRow
                    v-for="category in categories.data"
                    :key="category.slug"
                    :category
                />
            </Table>
        </PaginatedContent>
    </AdminLayout>
</template>
