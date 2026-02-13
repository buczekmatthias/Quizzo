import { computed } from 'vue';
import { pageProps } from '@/composables/useProps';
import type { Auth } from '@/types';

export const useCurrentUser = computed((): Auth => pageProps.value.auth);
