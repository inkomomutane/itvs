<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Search, PencilIcon, Trash2 } from 'lucide-vue-next';
import { h, PropType, ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { crudManager, t } from '@/lib/utils';
import Create from './Create.vue';
import Edit from './Edit.vue';
import Delete from './Delete.vue';
import { EducationDto, UserDto } from '@/types/generated';
import BaseContent from '@/layouts/BaseContent.vue';
import { Educations } from '@/types';
import VTable from '@/components/VTable/VTable.vue';
import VHeader from '@/components/VTable/VHeader.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import VCell from '@/components/VTable/VCell.vue';
import MemberRouteContent from '@/pages/MemberInfo/MemberRouteContent.vue';

const props = defineProps({
    educations: { type: Object as PropType<Educations> },
    member: {
        type: Object as PropType<UserDto>,
        required: true,
    },
});

const crudCreateManagerRef = ref(crudManager<null>());
const crudEditManagerRef = ref(crudManager<EducationDto>());
const crudDeleteManagerRef = ref(crudManager<EducationDto>());
const searchTerm = ref('');

const tableData = ref<EducationDto[]>([]);
watch(
    () => props.educations?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

watch(searchTerm, (value) => {
    router.visit(
        route('member.education.list', {
            search: value ?? '',
            member: props.member?.id,
        }),
        {
            only: ['educations'],
            replace: true,
            preserveState: true,
        },
    );
});

const columnHelper = createColumnHelper<EducationDto>();

const columns = [
    columnHelper.accessor('school', {
        header: ({ column }) => h(VHeader, { column, title: t('Institution') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('degree', {
        header: ({ column }) => h(VHeader, { column, title: t('Degree') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('field_of_study', {
        header: ({ column }) => h(VHeader, { column, title: t('Field of study') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('start_date', {
        header: ({ column }) => h(VHeader, { column, title: t('Start date') }),
        cell: (info) => h(VCell, { cell: info, value: new Date(info.getValue()).toLocaleDateString() }),
    }),
    columnHelper.accessor('end_date', {
        header: ({ column }) => h(VHeader, { column, title: t('End date') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() ? new Date(info.getValue()).toLocaleDateString() : t('Present') }),
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
                        onClick: () => crudEditManagerRef.value.open(row.original),
                    },
                    () => h(PencilIcon, { class: 'h-4 w-4' }),
                ),
                h(
                    Button,
                    {
                        variant: 'ghost',
                        size: 'sm',
                        class: 'text-red-500 hover:text-red-600',
                        onClick: () => crudDeleteManagerRef.value.open(row.original),
                    },
                    () => h(Trash2, { class: 'h-4 w-4' }),
                ),
            ]),
    }),
];
</script>

<template>
    <Head :title="$t('Formações')" />
    <AppLayout>
        <MemberRouteContent :member="member"  :title="$t('Formações')">
            <div class="w-full">
                <div class="mx-auto flex h-full max-w-7xl flex-1 flex-col gap-4 rounded-xl">
                    <Card class="rounded-sm shadow-none">
                        <CardHeader class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div class="relative w-full max-w-sm items-center">
                                <Input v-model="searchTerm" id="search" type="text" :placeholder="$t('Search') + '...'" class="pl-10" />
                                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                                    <Search class="size-4 text-muted-foreground" />
                                </span>
                            </div>
                            <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                                <Button @click="crudCreateManagerRef.open(null)">
                                    {{ $t('Add') }}
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent class="p-0">
                            <VTable v-model="tableData" :columns-defs="columns" />
                        </CardContent>
                        <CardFooter class="p-0">
                            <Pagination
                                :from="educations?.from"
                                :to="educations?.to"
                                :total="educations?.total"
                                :links="educations?.links"
                                :first_page_url="educations?.first_page_url"
                                :last_page_url="educations?.last_page_url"
                                :next_page_url="educations?.next_page_url"
                                :prev_page_url="educations?.prev_page_url"
                                class="p-4"
                            />
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </MemberRouteContent>
    </AppLayout>
    <Create :member="member" v-if="crudCreateManagerRef.isModalOpen" :openModal="crudCreateManagerRef.isModalOpen" :close="crudCreateManagerRef.close" />
    <Edit
        v-if="crudEditManagerRef.isModalOpen"
        :openModal="crudEditManagerRef.isModalOpen"
        :close="crudEditManagerRef.close"
        :member="member"
        :education="crudEditManagerRef.model"
    />
    <Delete
        v-if="crudDeleteManagerRef.isModalOpen"
        :member="member"
        :openModal="crudDeleteManagerRef.isModalOpen"
        :close="crudDeleteManagerRef.close"
        :education="crudDeleteManagerRef.model"
    />
</template>
