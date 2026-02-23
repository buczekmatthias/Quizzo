import { BadgeHelp, Home, LayoutGrid, Plus } from 'lucide-vue-next';
import { dashboard, home } from '@/routes';
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
            href: dashboard(),
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
                href: dashboard(),
                icon: LayoutGrid,
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
