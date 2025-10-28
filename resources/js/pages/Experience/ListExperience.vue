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
import { ExperienceDto, KeyValueDto } from '@/types/generated';
import BaseContent from '@/layouts/BaseContent.vue';
import { Experiences } from '@/types';
import VTable from '@/components/VTable/VTable.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import VHeader from '@/components/VTable/VHeader.vue';
import VCell from '@/components/VTable/VCell.vue';

const props = defineProps({
    experiences: {
        type: Object as PropType<Experiences>,
    },
    employment_types: {
        type: Array as PropType<Array<KeyValueDto>>,
        default: () => [],
    },
});

const crudCreateManagerRef = ref(crudManager<null>());
const crudEditManagerRef = ref(crudManager<ExperienceDto>());
const crudDeleteManagerRef = ref(crudManager<ExperienceDto>());
const searchTerm = ref('');

const tableData = ref<ExperienceDto[]>([]);
watch(
    () => props.experiences?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

watch(searchTerm, (value) => {
    router.visit(
        route('profile.experience.list', {
            search: value ?? '',
        }),
        {
            only: ['experiences'],
            replace: true,
            preserveState: true,
        },
    );
});

const columnHelper = createColumnHelper<ExperienceDto>();

const columns = [
    columnHelper.accessor('title', {
        header: ({ column }) => h(VHeader, { column, title: t('Title') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('company', {
        header: ({ column }) => h(VHeader, { column, title: t('Company') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('employment_type', {
        header: ({ column }) => h(VHeader, { column, title: t('Employment Type') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('location', {
        header: ({ column }) => h(VHeader, { column, title: t('Location') }),
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
    <Head :title="$t('Experiences')" />
    <AppLayout>
        <BaseContent :title="$t('Experiences')">
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
                                :from="experiences?.from"
                                :to="experiences.to"
                                :total="experiences.total"
                                :links="experiences?.links"
                                :first_page_url="experiences?.first_page_url"
                                :last_page_url="experiences?.last_page_url"
                                :next_page_url="experiences.next_page_url"
                                :prev_page_url="experiences?.prev_page_url"
                                class="p-4"
                            />
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </BaseContent>
    </AppLayout>
    <Create v-if="crudCreateManagerRef.isModalOpen" :openModal="crudCreateManagerRef.isModalOpen" :employment_types="employment_types" :close="crudCreateManagerRef.close" />
    <Edit
        v-if="crudEditManagerRef.isModalOpen"
        :employment_types="employment_types"
        :openModal="crudEditManagerRef.isModalOpen"
        :close="crudEditManagerRef.close"
        :experience="crudEditManagerRef.model"
    />
    <Delete
        v-if="crudDeleteManagerRef.isModalOpen"
        :openModal="crudDeleteManagerRef.isModalOpen"
        :close="crudDeleteManagerRef.close"
        :experience="crudDeleteManagerRef.model"
    />
</template>
