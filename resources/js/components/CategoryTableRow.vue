<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormBox from '@/components/FormBox.vue';
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
import admin from '@/routes/admin';
import type { Category } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';

type Props = {
    category: Category;
};

const props = defineProps<Props>();

const editFormOpen = ref<boolean>(false);
const editForm = useForm({
    name: props.category.name,
});

const submitEditForm = () => {
    editForm.patch(admin.categories.update(props.category).url, {
        only: ['categories'],
        onSuccess: () => {
            editForm.reset();
            editFormOpen.value = false;
        },
    });
};
</script>

<template>
    <TableRow>
        <TableCell>
            {{ category.slug }}
        </TableCell>
        <TableCell>
            {{ category.name }}
        </TableCell>
        <TableCell class="text-center">
            {{ category.quizzes_count }}
        </TableCell>
        <TableCell class="text-center">
            {{ category.users_count }}
        </TableCell>
        <TableCell>
            <Dialog v-model:open="editFormOpen">
                <DialogTrigger as-child>
                    <Button variant="outline" class="md:self-end">
                        Edit
                    </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-105">
                    <DialogHeader>
                        <DialogTitle>Update category</DialogTitle>
                        <DialogDescription>
                            Set new unique name for category
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4">
                        <FormBox required label="Name" id="name">
                            <Input
                                id="name"
                                name="name"
                                placeholder="Example category name..."
                                v-model="editForm.name"
                            />
                        </FormBox>
                    </div>
                    <Button type="submit" @click="submitEditForm">
                        Save changes
                    </Button>
                </DialogContent>
            </Dialog>
            <Button variant="destructive" as-child class="ml-2">
                <Link
                    :href="admin.categories.destroy(category)"
                    method="delete"
                >
                    Delete
                </Link>
            </Button>
        </TableCell>
    </TableRow>
</template>
