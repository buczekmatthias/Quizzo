<script setup lang="ts">
import { CheckIcon, ChevronDown } from 'lucide-vue-next';
import {
    ListboxContent,
    ListboxFilter,
    ListboxItem,
    ListboxItemIndicator,
    ListboxRoot,
    useFilter,
} from 'reka-ui';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverAnchor,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';
import type { Category } from '@/types';

type Props = {
    categories: Category[];
};

const props = defineProps<Props>();

const search = ref<string>('');
const open = ref<boolean>(false);

const model = defineModel<Category[]>();

const { contains } = useFilter({ sensitivity: 'base' });

const filteredCategories = computed(() =>
    search.value === ''
        ? props.categories
        : props.categories.filter((option: Category) =>
              contains(option.name.toLowerCase(), search.value.toLowerCase()),
          ),
);

watch(search, (f) => {
    if (f) {
        open.value = true;
    }
});
</script>

<template>
    <Popover v-model:open="open">
        <ListboxRoot v-model="model" highlight-on-hover multiple>
            <PopoverAnchor class="inline-flex w-full">
                <TagsInput
                    v-slot="{ modelValue: tags }"
                    v-model="model"
                    class="w-full"
                >
                    <TagsInputItem
                        v-for="item in tags as Category[]"
                        :key="item.slug"
                        :value="item.toString()"
                    >
                        <TagsInputItemText>
                            {{ item.name }}
                        </TagsInputItemText>
                        <TagsInputItemDelete />
                    </TagsInputItem>

                    <ListboxFilter v-model="search" as-child>
                        <TagsInputInput
                            placeholder="Categories..."
                            @keydown.enter.prevent
                            @keydown.down="open = true"
                        />
                    </ListboxFilter>

                    <PopoverTrigger as-child>
                        <Button
                            size="icon-sm"
                            variant="ghost"
                            class="order-last ml-auto self-start"
                        >
                            <ChevronDown class="size-3.5" />
                        </Button>
                    </PopoverTrigger>
                </TagsInput>
            </PopoverAnchor>

            <PopoverContent class="p-1" @open-auto-focus.prevent>
                <ListboxContent
                    class="max-h-[40vh] w-full scroll-py-1 overflow-x-hidden overflow-y-auto after:text-sm empty:p-1 empty:after:block empty:after:content-['No_categories']"
                    tabindex="0"
                >
                    <ListboxItem
                        v-for="item in filteredCategories"
                        :key="item.slug"
                        class="data[disabled:opacity-50 relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-disabled:pointer-events-none data-highlighted:bg-accent data-highlighted:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground"
                        :value="item"
                        @select="
                            () => {
                                search = '';
                            }
                        "
                    >
                        <span>{{ item.name }}</span>

                        <ListboxItemIndicator
                            class="ml-auto inline-flex items-center justify-center"
                        >
                            <CheckIcon />
                        </ListboxItemIndicator>
                    </ListboxItem>
                </ListboxContent>
            </PopoverContent>
        </ListboxRoot>
    </Popover>
</template>
