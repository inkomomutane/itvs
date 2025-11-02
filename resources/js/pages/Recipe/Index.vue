<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, h, PropType, ref, watch } from 'vue';
import { Users } from '@/types';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { PencilIcon, Search, Plus, CalendarIcon } from 'lucide-vue-next';
import { Button, buttonVariants } from '@/components/ui/button';
import Pagination from '@/components/Pagination.vue';
import Heading from '@/components/Heading.vue';
import { cn, crudManager, t } from '@/lib/utils';
import { KeyValueDto, UserDto } from '@/types/generated';
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
import { formatDate } from '../../helpers';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Calendar } from '@/components/ui/calendar';
const props = defineProps({
    recipes: {
        type: Object as PropType<Users>,
        required: true,
    },
    date: {
        type: String,
        default: today(getLocalTimeZone()).toDate().toISOString()
    },
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
        route('list-recipes', {
            search: value ?? '',
        }),
        {
            only: ['recipes','date'],
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
            route('list-recipes', {
                date: new CalendarDate(
                    (value.value as CalendarDate).year,
                    (value.value as CalendarDate).month,
                    (value.value as CalendarDate).day
                ).toString(),
                search: searchTerm.value ?? '',
            }),
            {
                only: ['recipes','date'],
                replace: true,
                preserveState: true,
            },
        );
    },
);

const crudManagerRef = ref(crudManager<UserDto>());
const editManagerRef = ref(crudManager<UserDto>());
const deleteManagerRef = ref(crudManager<UserDto>());

const tableData = ref<UserDto[]>([]);
watch(
    () => props.recipes?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

const columnHelper = createColumnHelper<UserDto>();

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
                                {{ t('Add') }}
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">



                        <div class="grid gap-4 p-4 md:grid-cols-2 lg:grid-cols-3">
                            <Item variant="outline" v-for="recipe in recipes.data" :key="recipe.id" class="hover:bg-accent/50 focus:bg-accent/50">
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
                                        {{ recipe.name }}
                                    </ItemTitle>
                                    <ItemDescription class="first-letter:capitalize">
                                        {{ formatDate(parseAbsolute(recipe.date)) }}
                                    </ItemDescription>
                                </ItemContent>
                                <ItemActions class="flex flex-col gap-2">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        aria-label="Invite"
                                        @click="editManagerRef.open(recipe)"
                                    >
                                        {{ t('Edit') }}
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        aria-label="Invite"
                                        @click="deleteManagerRef.open(recipe)"
                                    >
                                      {{ t('Delete') }}
                                    </Button>
                                </ItemActions>
                            </Item>
                        </div>


                    </CardContent>
                    <CardFooter class="p-0">
                        <Pagination
                            :from="recipes?.from"
                            :to="recipes.to"
                            :total="recipes.total"
                            :links="recipes?.links"
                            :first_page_url="recipes?.first_page_url"
                            :last_page_url="recipes?.last_page_url"
                            :next_page_url="recipes.next_page_url"
                            :prev_page_url="recipes?.prev_page_url"
                            class="p-4"
                        />
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
    <Create v-if="crudManagerRef.isModalOpen"  :date="formatedDate" :openModal="crudManagerRef.isModalOpen" :close="crudManagerRef.close" />
    <Edit
        v-if="editManagerRef.isModalOpen"
        :openModal="editManagerRef.isModalOpen"
        :close="editManagerRef.close"
        :recipe="editManagerRef.model"
        :date="value"
    />

    <Delete
        v-if="deleteManagerRef.isModalOpen"
        :openModal="deleteManagerRef.isModalOpen"
        :close="deleteManagerRef.close"
        :recipe="deleteManagerRef.model"
    />
   </template>
