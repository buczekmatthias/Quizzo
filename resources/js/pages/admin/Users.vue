<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { capitalize } from 'vue';
import PaginatedContent from '@/components/PaginatedContent.vue';
import Table from '@/components/Table.vue';
import TableActions from '@/components/TableActions.vue';
import {
    DropdownMenuItem,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import AdminLayout from '@/layouts/AdminLayout.vue';
import admin from '@/routes/admin';
import type { Pagination, User } from '@/types';
import { type BreadcrumbItem } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';

type Props = {
    users: Pagination & { data: User[] };
    can_manage: boolean;
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: admin.users.index().url,
    },
];

const headers = [
    {
        content: 'Name',
    },
    {
        content: 'Username',
    },
    {
        content: 'Email',
    },
    {
        content: 'Role',
    },
    {
        content: 'Quizzes',
    },
];
</script>

<template>
    <Head title="Users" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <PaginatedContent :pagination="users" preserve-scroll>
            <Table :headers has-actions>
                <TableRow v-for="user in users.data" :key="user.username">
                    <TableCell>
                        {{ user.name }}
                    </TableCell>
                    <TableCell>
                        {{ user.username }}
                    </TableCell>
                    <TableCell>
                        {{ user.email }}
                    </TableCell>
                    <TableCell>
                        {{ capitalize(user.role) }}
                    </TableCell>
                    <TableCell class="text-center">
                        {{ user.quizzes.length }}
                    </TableCell>
                    <template v-if="can_manage && user.role !== 'admin'">
                        <TableActions>
                            <DropdownMenuItem
                                as-child
                                class="w-full cursor-pointer"
                                v-if="user.role !== 'admin'"
                                method="delete"
                                :only="['users']"
                            >
                                <Link :href="admin.users.destroy(user)">
                                    Delete user
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem
                                as-child
                                class="w-full cursor-pointer"
                                v-if="user.role !== 'admin'"
                            >
                                <Link
                                    :href="admin.users.update(user)"
                                    :data="{ role: 'admin' }"
                                    method="patch"
                                    :only="['users']"
                                >
                                    Promote to admin
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                as-child
                                class="w-full cursor-pointer"
                                v-if="user.role === 'user'"
                            >
                                <Link
                                    :href="admin.users.update(user)"
                                    :data="{ role: 'mod' }"
                                    method="patch"
                                    :only="['users']"
                                >
                                    Promote to mod
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                as-child
                                class="w-full cursor-pointer"
                                v-if="user.role !== 'user'"
                            >
                                <Link
                                    :href="admin.users.update(user)"
                                    :data="{ role: 'user' }"
                                    method="patch"
                                    :only="['users']"
                                >
                                    Demote to user
                                </Link>
                            </DropdownMenuItem>
                        </TableActions>
                    </template>
                </TableRow>
            </Table>
        </PaginatedContent>
    </AdminLayout>
</template>
