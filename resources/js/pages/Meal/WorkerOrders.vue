<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router,useForm } from '@inertiajs/vue3';
import { computed, h, PropType, ref, watch } from 'vue';
import { Users } from '@/types';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { PencilIcon, Search, Plus, CalendarIcon } from 'lucide-vue-next';
import { Button, buttonVariants } from '@/components/ui/button';
import Pagination from '@/components/Pagination.vue';
import Heading from '@/components/Heading.vue';
import { cn, crudManager, t } from '@/lib/utils';
import { KeyValueDto, RecipeDto, UserDto } from '@/types/generated';
import Create from './Create.vue';
import Edit from './Edit.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import VTable from '@/components/VTable/VTable.vue';
import VHeader from '@/components/VTable/VHeader.vue';
import VCell from '@/components/VTable/VCell.vue';
import Delete from './Delete.vue';
import { Item, ItemActions, ItemContent, ItemDescription, ItemMedia, ItemTitle } from '@/components/ui/item';
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    parseAbsolute,
    today,
    DateValue,
    CalendarDate
} from '@internationalized/date';
import food from '@/images/food.svg';
import { formatDate } from '@/helpers';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Calendar } from '@/components/ui/calendar';
import { Separator } from '@/components/ui/separator';
import { Badge } from '@/components/ui/badge';
const props = defineProps({
    recipes: {
        type: Object as PropType<Users>,
        required: true,
        default: []
    },
    orders: {
        type: Object as PropType<Users>,
        required: true,
        default: []
    },
    date: {
        type: String,
        default: today(getLocalTimeZone()).toDate().toISOString()
    },
    periods : {
        type: Array<KeyValueDto>,
        default: () => []
    }
});

const items = [
    { value: 0, label: t('Today') },
    { value: 1, label: t('Tomorrow') },
    { value: 2, label: t('In 2 days') },
    { value: 3, label: t('In 3 days') },
    { value: 4, label: t('In 4 days') },
    { value: 5, label: t('In 5 days') },
    { value: 6, label: t('In 6 days') },
];

const tryParseDate = (dateStr: string): Date => {
    try {
        return parseDate(dateStr, getLocalTimeZone());
    } catch {
        return parseAbsolute(dateStr);
    }
};



const value = ref<DateValue>(tryParseDate(props.date));
const formatedDate = computed(()=> {
    const auxDate = new Date();
    if (!value.value) return new CalendarDate(auxDate.getFullYear(),auxDate.getMonth()+1,auxDate.getDate()).toString();
    return new CalendarDate(
        (value.value as CalendarDate).year,
        (value.value as CalendarDate).month,
        (value.value as CalendarDate).day
    ).toString();
})

const searchTerm = ref('');
watch(searchTerm, (value) => {
    router.visit(
        route('worker-meals-unconfirmed', {
            search: value ?? '',
        }),
        {
            only: ['recipes','date','orders'],
            replace: true,
            preserveState: true,
        },
    );
});

watch(
    () => value.value,
    (newValue) => {
        if (!newValue) return;
        router.visit(
            route('worker-meals-unconfirmed', {
                date: new CalendarDate(
                    (value.value as CalendarDate).year,
                    (value.value as CalendarDate).month,
                    (value.value as CalendarDate).day
                ).toString(),
                search: searchTerm.value ?? '',
            }),
            {
                only: ['recipes','date','orders'],
                replace: true,
                preserveState: true,
            },
        );
    },
);

const crudManagerRef = ref(crudManager<UserDto>());
const editManagerRef = ref(crudManager<UserDto>());
const deleteManagerRef = ref(crudManager<UserDto>());


const form = useForm({});

const orderMeal = (recipe: RecipeDto) => form.post(route('order-meal',{
     recipe: recipe.id,
}))


</script>

<template>
    <Head :title="t('Recipes')" />
    <AppLayout>

        <div class="w-full mt-16">
            <div class="mx-auto flex h-full max-w-7xl flex-1 flex-col gap-4 rounded-xl">
                <Card class="rounded-sm shadow-none">
                    <CardHeader class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
                        <div class="flex flex-row items-center gap-4">
                            <div>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button
                                            variant="outline"
                                            :class="cn(  'justify-start text-left font-normal',!value && 'text-muted-foreground')"
                                        >
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ value ? formatDate(value) : 'Pick a date' }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="flex min-w-32 w-full flex-col gap-y-2 p-2">
                                        <Select
                                            @update:model-value="(v) => {
                                                  if (!v) return;
                                                  value = today(getLocalTimeZone()).add({ days: Number(v) });
                                                }"
                                        >
                                            <SelectTrigger class="w-full min-w-32 justify-between">
                                                <SelectValue placeholder="Select" class="w-full min-w-32 justify-between" />
                                            </SelectTrigger>
                                            <SelectContent class="!w-full">
                                                <SelectItem v-for="item in items" :key="item.value"
                                                            :value="item.value.toString()">
                                                    {{ item.label }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <Calendar locale="pt" :min-value="today(getLocalTimeZone())" v-model="value" />
                                    </PopoverContent>
                                </Popover>
                            </div>
                            <div class="relative w-full max-w-sm items-center">
                                <Input v-model="searchTerm" id="search" type="text" :placeholder="t('Search') + '...'" class="pl-10" />
                                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                                <Search class="size-4 text-muted-foreground" />
                            </span>
                            </div>
                        </div>

                        <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                            <Button @click="crudManagerRef.open(null)">
                                {{ t('Order meal') }}
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Separator/>
                        <div class="space-y-1 p-4">
                            <h4 class="text-sm font-medium leading-none">
                                {{ t('Ordered meal') }}
                            </h4>
                        </div>

                        <div class="grid gap-4 p-4 md:grid-cols-2 lg:grid-cols-3">
                            <Item variant="outline" v-for="order in orders" :key="order.id" class="hover:bg-accent/50 focus:bg-accent/50">
                                <ItemMedia variant="image" class="bg-zinc-950/10 dark:bg-white/10 rounded-0 p-1">
                                    <img
                                        :src="food"
                                        alt="Avatar"
                                        width="32"
                                        height="32"
                                        class="h-8 w-8 rounded-md object-cover"
                                    >
                                </ItemMedia>
                                <ItemContent>
                                    <ItemTitle>
                                        {{ order.recipe_name }}
                                    </ItemTitle>
                                    <ItemDescription class="first-letter:capitalize">
                                        {{ formatDate(parseAbsolute(order.meal_date)) }}
                                    </ItemDescription>
                                </ItemContent>
                                <ItemActions class="flex flex-col gap-2">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        aria-label="Invite"
                                    >
                                        {{ t('Confirm') }}
                                    </Button>
                                </ItemActions>
                            </Item>
                        </div>
                          <Separator/>
                            <div class="space-y-1 p-4">
                                <h4 class="text-sm font-medium leading-none">
                                    {{ t('Today menu') }}
                                </h4>
                                <p class="text-sm text-muted-foreground">
                                    {{ t('All menus available for') }} :  {{ formatDate(value) }}
                                </p>
                            </div>
                        <div>
                        </div>
                        <div class="flex gap-4 p-4">

                            <div v-for="recipe in recipes" :key="recipe.id" class=" rounded-3xl bg-black/5 p-2 outline outline-white/15 backdrop-blur-md dark:bg-white/10 h-full ">
                                <div class=" flex  flex-col rounded-2xl bg-white outline outline-black/5 dark:bg-gray-950  gap-0 items-center xs:flex-row ">
                                    <img alt="" class="size-48 object-cover p-8 transition-[border-radius] duration-350 rounded-md w-72"
                                         :src="food">
                                    <div class="flex flex-col items-start xs:items-start px-7 pb-7 w-80 ">
                                        <span
                                        class="text-gray-950 transition-[font-size] duration-350 dark:text-white text-2xl font-medium"
                                    >
                                        {{ recipe.name }}
                                    </span>
                                        <div class="text-gray-950 transition-colors duration-350 dark:text-white font-medium text-sky-500!">
                                            <div>
                                                <ItemDescription class="first-letter:capitalize">
                                                    <Badge variant="outline">{{ t(recipe.period) }}</Badge>
                                                </ItemDescription>
                                            </div>
                                        <div v-html="recipe.description"> </div>
                                    </div>
                                        <span class="flex text-gray-950 transition-colors duration-350 dark:text-white gap-2 font-medium text-gray-600! dark:text-gray-400! f">
                                            <span class="first-letter:uppercase">{{ formatDate(parseAbsolute(recipe.date)) }}</span>
                                        </span>
                                    </div>
                                    <div class="w-full">
                                        <div class="mx-4">
                                            <Button
                                                variant="outline"
                                                :disabled="form.processing"
                                                @click="orderMeal(recipe)"
                                                class="w-full mb-4  mt-0 xs:mt-4 xs:mb-0"
                                            >
                                                {{ t('Order meal') }}
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
