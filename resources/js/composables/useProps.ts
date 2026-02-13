import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { AppPageProps } from '@/types';

export const pageProps = computed((): AppPageProps => usePage().props);

export const useAppName = computed((): string => pageProps.value.name);
