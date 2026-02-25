<script setup lang="ts">
import {
    Table,
    TableBody,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

type Props = {
    headers: {
        content: string;
        class?: string;
    }[];
    hasActions?: boolean;
};

defineProps<Props>();
</script>

<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead
                    v-for="header in headers"
                    :key="header.content"
                    :class="header.class"
                >
                    {{ header.content }}
                </TableHead>
                <TableHead class="w-1" v-if="hasActions"></TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <slot />
        </TableBody>
    </Table>
</template>
