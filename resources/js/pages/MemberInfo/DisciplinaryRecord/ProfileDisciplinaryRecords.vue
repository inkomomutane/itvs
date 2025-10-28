<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Search, EyeIcon } from 'lucide-vue-next';
import { h, ref, watch, PropType } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { crudManager, t } from '@/lib/utils';
import { DisciplinaryRecordDto, UserDto } from '@/types/generated';
import BaseContent from '@/layouts/BaseContent.vue';
import { DisciplinaryRecords } from '@/types';
import VTable from '@/components/VTable/VTable.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import VHeader from '@/components/VTable/VHeader.vue';
import VCell from '@/components/VTable/VCell.vue';
import MemberDisciplinaryRecord from '@/pages/DisciplinaryRecord/MemberDisciplinaryRecord.vue';
import MemberRouteContent from '@/pages/MemberInfo/MemberRouteContent.vue';

const props = defineProps({
    disciplinary_records: {
        type: Object as PropType<DisciplinaryRecords>,
    },
    member: {
        type: Object as PropType<UserDto>,
        required: true,
    },
});

const crudViewManagerRef = ref(crudManager<DisciplinaryRecordDto>());
const searchTerm = ref('');

const tableData = ref<DisciplinaryRecordDto[]>([]);
watch(
    () => props.disciplinary_records?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

watch(searchTerm, (value) => {
    router.visit(
        route('profile.disciplinary-record.list', {
            search: value ?? '',
        }),
        {
            only: ['disciplinary_records'],
            replace: true,
            preserveState: true,
        },
    );
});

const columnHelper = createColumnHelper<DisciplinaryRecordDto>();

const columns = [
    columnHelper.accessor('infraction', {
        header: ({ column }) => h(VHeader, { column, title: t('Infraction') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('penalty', {
        header: ({ column }) => h(VHeader, { column, title: t('Penalty') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('organ', {
        header: ({ column }) => h(VHeader, { column, title: t('Organ') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('process_number', {
        header: ({ column }) => h(VHeader, { column, title: t('Process Number') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('record_date', {
        header: ({ column }) => h(VHeader, { column, title: t('Record Date') }),
        cell: (info) => h(VCell, { cell: info, value: new Date(info.getValue()).toLocaleDateString() }),
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
                        onClick: () => crudViewManagerRef.value.open(row.original),
                    },
                    () => h(EyeIcon, { class: 'h-4 w-4' }),
                ),
            ]),
    }),
];
</script>

<template>
    <Head :title="$t('Disciplinary Records')" />
    <AppLayout>
        <MemberRouteContent :member="member"  :title="$t('Disciplinary Records')">
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
                        </CardHeader>
                        <CardContent class="p-0">
                            <VTable v-model="tableData" :columns-defs="columns" />
                        </CardContent>
                        <CardFooter class="p-0">
                            <Pagination
                                :from="disciplinary_records?.from"
                                :to="disciplinary_records.to"
                                :total="disciplinary_records.total"
                                :links="disciplinary_records?.links"
                                :first_page_url="disciplinary_records?.first_page_url"
                                :last_page_url="disciplinary_records?.last_page_url"
                                :next_page_url="disciplinary_records.next_page_url"
                                :prev_page_url="disciplinary_records?.prev_page_url"
                                class="p-4"
                            />
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </MemberRouteContent>
    </AppLayout>
     <MemberDisciplinaryRecord
        v-if="crudViewManagerRef.isModalOpen"
        :openModal="crudViewManagerRef.isModalOpen"
        :close="crudViewManagerRef.close"
        :disciplinary-record="crudViewManagerRef.model"
    />
</template>
