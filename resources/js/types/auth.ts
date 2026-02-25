import type { Quiz } from '.';

export type User = {
    name: string;
    username: string;
    email: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
    quizzes: Quiz[];
    role: string;
};

export type Auth = {
    user: User;
    hasSpecialPermissions: boolean;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
