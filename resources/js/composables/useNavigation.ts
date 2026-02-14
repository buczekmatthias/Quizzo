import { BadgeHelp, Home, LayoutGrid } from 'lucide-vue-next';
import { dashboard, home } from '@/routes';
import quizes from '@/routes/quizes';
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
                title: 'Quizes',
                href: quizes.index(),
                icon: BadgeHelp,
            },
        ],
        footer: [],
    };

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
