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

export type Permissions = {
    [key: string]: boolean;
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
    has_finished: boolean;
    can_be_done: boolean;
    did_user_do: boolean;
    token: string;
};

export type PaginatedQuiz = Pagination & { data: Quiz[] };

export type Question = {
    slug: string;
    content: string;
    has_image: boolean;
    image: string;
    answers: Answer[];
};

export type Answer = {
    slug: string;
    content: string;
    is_content_file_type: boolean;
    has_user_select_this_answer: boolean;
    is_correct_answer: boolean;
};

export type QuizFormQuestion = {
    slug: string;
    answer_selected_slug: string;
};
