import type { NavItem } from '@/types';
import { GraduationCap, LayoutGrid, Users,ListCheck,ListChecks } from 'lucide-vue-next';
import {t} from "@/lib/utils"

export const AppRoutes: NavItem[]  = [
    {
        title: t('Dashboard'),
        href: route('dashboard'),
        icon: GraduationCap,
        routeName: 'dashboard',
        adminOnly: false,
    },

];
