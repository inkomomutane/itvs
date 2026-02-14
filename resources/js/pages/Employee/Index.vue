<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { h, PropType, ref, watch } from 'vue';
import { Users } from '@/types';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { PencilIcon, Search, Trash2,EllipsisVertical  } from 'lucide-vue-next';
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
import {
    DropdownMenu, DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';

const props = defineProps({
    employees: {
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
        route('list-employees', {
            search: value ?? '',
        }),
        {
            only: ['employees','roles'],
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
    () => props.employees?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

const columnHelper = createColumnHelper<UserDto>();
const columns = [
    columnHelper.accessor('name', {
        header: ({ column }) => h(VHeader, { column, title: t('Nome') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('sap_number', {
        header: ({ column }) => h(VHeader, { column, title: t('Número de SAP') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('role', {
        header: ({ column }) => h(VHeader, { column, title: t('Cargo') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.display({
        id: 'actions',
        header: () => t('Ações'),
        cell: ({ row }) => h(DropdownMenu, {}, {
            default: () => [
                h(DropdownMenuTrigger, { asChild: true }, {
                    default: () => h(Button, {
                        variant: 'ghost',
                        class: 'h-8 w-8 p-0',
                    }, {
                        default: () => [
                            h('span', { class: 'sr-only' }, 'Open menu'),
                            h(EllipsisVertical, { class: 'h-4 w-4' }),
                        ],
                    }),
                }),
                h(DropdownMenuContent, { align: 'end' }, {
                    default: () => [
                        h(DropdownMenuItem, {
                                onClick: () => editManagerRef.value.open(row.original)
                        }, () => 'Edit'),
                        h(DropdownMenuSeparator, {}),
                        h(DropdownMenuItem, {
                            variant: 'default',
                             onClick: () =>  deleteManagerRef.value.open(row.original),
                        }, () => 'Delete'),
                    ],
                }),
            ],
        }),

    }),
];
</script>

<template>
    <Head :title="t('Employees')" />
    <AppLayout>

        <div class="w-full mt-16">
            <div class="mx-auto flex h-full  flex-1 flex-col gap-4 rounded-xl p-4 px-12 xl:px-8">
                <Card class="shadow-none border-none">
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
                    <CardContent class="p-4 ">
                        <VTable v-model="tableData" :columns-defs="columns" :pinning="{
                            right: ['actions']
                        }" />
                    </CardContent>
                    <CardFooter class="p-0">
                        <Pagination
                            :from="employees?.from"
                            :to="employees.to"
                            :total="employees.total"
                            :links="employees?.links"
                            :first_page_url="employees?.first_page_url"
                            :last_page_url="employees?.last_page_url"
                            :next_page_url="employees.next_page_url"
                            :prev_page_url="employees?.prev_page_url"
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
        :employee="editManagerRef.model"
    />

    <Delete
        v-if="deleteManagerRef.isModalOpen"
        :openModal="deleteManagerRef.isModalOpen"
        :close="deleteManagerRef.close"
        :employee="deleteManagerRef.model"
    />
   </template>
