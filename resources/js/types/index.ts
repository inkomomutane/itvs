import type { LucideIcon } from 'lucide-vue-next';
import { EducationDto, ExperienceDto, UserDto, DisciplinaryRecordDto } from '@/types/generated';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    adminOnly?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: {
        location: string;
        url: string;
        port: null | number;
        defaults: Record<string, unknown>;
        routes: Record<string, string>;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}


export interface Educations extends Omit<EducationDto, 'data'> {
    data: Array<EducationDto>;
}

export interface Users extends Omit<UserDto, 'data'> {
    data: Array<UserDto>;
}


export interface Experiences extends Omit<ExperienceDto, 'data'> {
    data: Array<ExperienceDto>;
}

export interface DisciplinaryRecords extends Omit<DisciplinaryRecordDto, 'data'> {
    data: Array<DisciplinaryRecordDto>;
}
//

export type BreadcrumbItemType = BreadcrumbItem;
