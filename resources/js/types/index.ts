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



export type UserRole = 'super_admin' | 'admin' | 'chef' | 'employee';

export type MealType = 'breakfast' | 'lunch' | 'dinner' | 'snack';

export type ReservationStatus = 'reserved' | 'consumed' | 'absent';

export interface User {
    id: string;
    name: string;
    role: UserRole;
    sap_number?: string;
    company?: string;
    position?: string;
    password?: string;
    is_active: boolean;
    created_at: string;
}

export interface MenuItem {
    id: string;
    name: string;
    description?: string;
    meal_type: MealType;
    date: string;
    option_number: number;
    is_available: boolean;
    created_by: string;
    created_at: string;
}

export interface Reservation {
    id: string;
    employee_id: string;
    employee_name: string;
    employee_sap: string;
    menu_item_id: string;
    menu_item_name: string;
    meal_type: MealType;
    date: string;
    status: ReservationStatus;
    reserved_at: string;
    consumed_at?: string;
}

export interface DayOption {
    date: string;
    dayName: string;
    dayNumber: number;
    isToday: boolean;
}

export const MEAL_LABELS: Record<MealType, string> = {
    breakfast: 'Pequeno Almoço',
    lunch: 'Almoço',
    dinner: 'Jantar',
    snack: 'Ceia',
};

export const MEAL_TIMES: Record<MealType, string> = {
    breakfast: '06:00 - 09:00',
    lunch: '12:00 - 14:00',
    dinner: '18:00 - 20:00',
    snack: '21:00 - 22:00',
};

