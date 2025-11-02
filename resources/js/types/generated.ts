export type AlertDto = {
    type: AlertStatus;
    message: string;
};
export type AlertStatus = 'success' | 'error' | 'info' | 'warning';
export type MaritalStatus = 'single' | 'married' | 'divorced' | 'widowed';
export type MealDto = {
    id: number | null;
    recipe_id: string;
    worker_id: string;
    meal_date: any | null;
    reserved_at: any | null;
    served_at: any | null;
    period: MealPeriod | null;
    active: boolean | null;
};
export type MealPeriod = 'Breakfast' | 'Lunch' | 'Dinner';
export type MealStatus = 'Reserved' | 'Eaten' | 'Canceled';
export type RecipeDto = {
    id: string | null;
    name: string;
    date: any;
    description: string | null;
    ingredients: string | null;
    instructions: string | null;
    active: boolean | null;
};
export type RoleDto = {
    id: string | null;
    name: string;
};
export type Sex = 'male' | 'female' | 'other';
export type UserDto = {
    id: string | null;
    name: string;
    email: string;
    password: string | null;
    role: string | null;
};
