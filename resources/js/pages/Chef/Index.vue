<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { h, PropType, ref, watch } from 'vue';
import { Users } from '@/types';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { PencilIcon, Search, Trash2 } from 'lucide-vue-next';
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

const props = defineProps({
    chefs: {
        type: Object as PropType<Users>,
        required: true,
    },
    roles: {
        type:  Array<RoleDto>,
        default: []
    }
});

const searchTerm = ref('');
watch(searchTerm, (value) => {
    router.visit(
        route('list-chefs', {
            search: value ?? '',
        }),
        {
            only: ['chefs','roles'],
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
    () => props.chefs?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

const columnHelper = createColumnHelper<UserDto>();
const columns = [
    columnHelper.accessor('name', {
        header: ({ column }) => h(VHeader, { column, title: t('Name') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('email', {
        header: ({ column }) => h(VHeader, { column, title: t('Email') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('role', {
        header: ({ column }) => h(VHeader, { column, title: t('Role') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.display({
        id: 'actions',
        header: () => t('Actions'),
        cell: ({ row }) =>
            h('div', { class: 'flex items-center space-x-2' }, [
                h(
                    Button,
                    {
                        variant: 'ghost',
                        size: 'sm',
                        class: 'text-blue-500 hover:text-blue-600',
                        onClick: () => editManagerRef.value.open(row.original),
                    },
                    () => h(PencilIcon, { class: 'h-4 w-4' }),
                ),
                h(
                    Button,
                    {
                        variant: 'ghost',
                        size: 'sm',
                        class: 'text-red-500 hover:text-red-600',
                        onClick: () => deleteManagerRef.value.open(row.original),
                    },
                    () => h(Trash2, { class: 'h-4 w-4' }),
                ),
            ]),
    }),
];
</script>

<template>
    <Head :title="t('Employees')" />
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
                        <VTable v-model="tableData" :columns-defs="columns"  :pinning="{
                            right: ['actions']
                        }"/>
                    </CardContent>
                    <CardFooter class="p-0">
                        <Pagination
                            :from="chefs?.from"
                            :to="chefs.to"
                            :total="chefs.total"
                            :links="chefs?.links"
                            :first_page_url="chefs?.first_page_url"
                            :last_page_url="chefs?.last_page_url"
                            :next_page_url="chefs.next_page_url"
                            :prev_page_url="chefs?.prev_page_url"
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
