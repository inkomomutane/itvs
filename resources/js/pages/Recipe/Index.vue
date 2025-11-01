<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { h, PropType, ref, watch } from 'vue';
import { Users } from '@/types';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { PencilIcon, Search, Plus } from 'lucide-vue-next';
import { Button, buttonVariants } from '@/components/ui/button';
import Pagination from '@/components/Pagination.vue';
import Heading from '@/components/Heading.vue';
import { crudManager, t } from '@/lib/utils';
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
    parseAbsolute
} from '@internationalized/date';
import food from '@/images/food.svg';
const props = defineProps({
    recipes: {
        type: Object as PropType<Users>,
        required: true,
    },
    date: {
        type: String,
        default: new Date(),
    },
});


const df = new DateFormatter('en-US', {
    dateStyle: 'long'
});

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
                        <div class="relative w-full max-w-sm items-center">
                            <Input v-model="searchTerm" id="search" type="text" :placeholder="t('Search') + '...'" class="pl-10" />
                            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                                <Search class="size-4 text-muted-foreground" />
                            </span>
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
                                    <ItemDescription>
                                        <!-- internationzlized/date  -->

                                        {{  df.format(parseAbsolute(recipe.date).toDate(getLocalTimeZone())) }}
                                    </ItemDescription>
                                </ItemContent>
                                <ItemActions>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        aria-label="Invite"
                                        @click="editManagerRef.open(recipe)"
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
    <Create v-if="crudManagerRef.isModalOpen" :roles="roles" :openModal="crudManagerRef.isModalOpen" :close="crudManagerRef.close" />
    <Edit
        v-if="editManagerRef.isModalOpen"
        :roles="roles"
        :openModal="editManagerRef.isModalOpen"
        :close="editManagerRef.close"
        :chef="editManagerRef.model"
    />

    <Delete
        v-if="deleteManagerRef.isModalOpen"
        :openModal="deleteManagerRef.isModalOpen"
        :close="deleteManagerRef.close"
        :chef="deleteManagerRef.model"
    />
   </template>
