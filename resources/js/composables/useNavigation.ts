import {
    BadgeHelp,
    BadgeInfo,
    FileQuestion,
    FolderOpen,
    Folders,
    Home,
    LayoutGrid,
    Plus,
    User,
} from 'lucide-vue-next';
import { home } from '@/routes';
import admin from '@/routes/admin';
import categories from '@/routes/categories';
import quizzes from '@/routes/quizzes';
import type { Navigation } from '@/types';
import { useCurrentUser } from './useUser';

export function useAppNavigation(): Navigation {
    const links: Navigation = {
        main: [
            {
                title: 'Homepage',
                href: home(),
                icon: Home,
            },
            {
                title: 'Quizzes',
                href: quizzes.index(),
                icon: BadgeHelp,
            },
            {
                title: 'Categories',
                href: categories.index(),
                icon: FolderOpen,
            },
        ],
        footer: [],
    };

    if (useCurrentUser.value.user) {
        links.footer.push({
            title: 'Create new quiz',
            href: quizzes.create(),
            icon: Plus,
        });
    }

    if (useCurrentUser.value.hasSpecialPermissions) {
        links.footer.push({
            title: 'Dashboard',
            href: admin.dashboard(),
            icon: LayoutGrid,
        });
    }

    return links;
}

export function useAdminNavigation(): Navigation {
    return {
        main: [
            {
                title: 'Dashboard',
                href: admin.dashboard(),
                icon: LayoutGrid,
            },
            {
                title: 'Categories',
                href: admin.categories.index(),
                icon: FolderOpen,
            },
            {
                title: 'Users',
                href: admin.users.index(),
                icon: User,
            },
            {
                title: 'Quizzes',
                href: admin.quizzes.index(),
                icon: Folders,
            },
            {
                title: 'Questions',
                href: admin.questions.index(),
                icon: FileQuestion,
            },
            {
                title: 'Answers',
                href: admin.answers.index(),
                icon: BadgeInfo,
            },
        ],
        footer: [
            {
                title: 'Homepage',
                href: home(),
                icon: Home,
            },
        ],
    };
}
