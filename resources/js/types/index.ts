export * from './auth';
export * from './navigation';
export * from './ui';

import type { Auth } from './auth';

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};

export type Pagination = {
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        to: number;
        total: number;
    };
    links: {
        prev: string;
        next: string;
    };
};

export type Category = {
    slug: string;
    name: string;
    is_favorite: boolean;
};

export type Quiz = {
    slug: string;
    title: string;
    description?: string;
    is_public: boolean;
    started_at: string;
    finished_at: string;
};

export type PaginatedQuiz = Pagination & { data: Quiz[] };
