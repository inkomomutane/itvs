<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Search, PencilIcon, Trash2, Plus, CalendarIcon, Clock } from 'lucide-vue-next';
import { h, PropType, ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { Input } from '@/components/ui/input';
import { Button, buttonVariants } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import { crudManager, t } from '@/lib/utils';
import { PresenceRegisterDto } from '@/types/generated';
import VTable from '@/components/VTable/VTable.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import VHeader from '@/components/VTable/VHeader.vue';
import VCell from '@/components/VTable/VCell.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle
} from '@/components/ui/dialog';
import InputError from '@/components/InputError.vue';
import Heading from '@/components/Heading.vue';

const props = defineProps({
    presences: {
        type: Array,
        required: true,
    },
    date: {
        type: String,
        default: () => new Date().toISOString().split('T')[0],
    },
});

const searchTerm = ref('');
const selectedDate = ref(new Date(props.date));
const crudEditManagerRef = ref(crudManager<PresenceRegisterDto>());

const tableData = ref<PresenceRegisterDto[]>([]);

watch(
    () => props.presences,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

watch(searchTerm, (value) => {
    router.visit(
        route('members.presence-register.list', {
            search: value ?? '',
            date: selectedDate.value.toISOString().split('T')[0],
        }),
        {
            only: ['presences'],
            replace: true,
            preserveState: true,
        },
    );
});

watch(selectedDate, (value) => {
    router.visit(
        route('members.presence-register.list', {
            search: searchTerm.value ?? '',
            date: value.toISOString().split('T')[0],
        }),
        {
            only: ['presenceRegisters'],
            replace: true,
            preserveState: true,
        },
    );
});



const columnHelper = createColumnHelper<PresenceRegisterDto>();

const columns = [
    columnHelper.accessor('member', {
        header: ({ column }) => h(VHeader, { column, title: t('Member') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('email', {
        header: ({ column }) => h(VHeader, { column, title: t('NIP') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('0', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 1') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('1', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 2') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('2', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 3') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('3', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 4') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('4', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 5') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('5', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 6') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('6', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 7') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('7', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 8') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('8', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 9') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('9', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 10') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('10', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 11') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('11', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 12') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('12', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 13') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('13', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 14') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('14', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 15') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('15', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 16') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('16', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 17') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('17', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 18') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('18', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 19') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('19', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 20') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('20', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 21') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('21', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 22') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('22', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 23') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('23', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 24') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('24', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 25') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('25', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 26') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('26', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 27') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('27', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 28') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('28', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 29') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('29', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 30') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
    columnHelper.accessor('30', {
        header: ({ column }) => h(VHeader, { column, title: t('Dia 31') }),
        cell: (info) => h(VCell, {
            cell: info,
            value: info.getValue()
        }),
    }),
];
</script>

<template>
    <Head :title="$t('Mapa de efectividade')" />
    <AppLayout>
        <Card class="mt-0 rounded-none bg-white px-4 pt-6 shadow-none dark:bg-zinc-950">
            <Heading class="mb-2 pb-0" :title="$t('Mapa de efectividade')" :description="$t('Mapa de efectividade')" />
        </Card>
        <div class="w-full">
            <div class="mx-auto flex h-full max-w-7xl flex-1 flex-col gap-4 rounded-xl">
                <Card class="rounded-sm shadow-none">
                    <CardHeader class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
                        <div class="flex items-center space-x-4">
                            <!-- Date Picker -->
                            <div class="flex items-center space-x-2">
                                <CalendarIcon class="h-4 w-4 text-muted-foreground" />
                                <VueDatePicker
                                    v-model="selectedDate"
                                    format="dd/MM/yyyy"
                                    auto-apply
                                    :placeholder="$t('Select Date')"
                                    class="w-40"
                                />
                            </div>

                            <!-- Search -->
                            <div class="relative w-full max-w-sm items-center">
                                <Input
                                    v-model="searchTerm"
                                    id="search"
                                    type="text"
                                    :placeholder="$t('Search') + '...'"
                                    class="pl-10"
                                />
                                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                                        <Search class="size-4 text-muted-foreground" />
                                    </span>
                            </div>
                        </div>

                        <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                            <a :href="route('member.month.presence-extractor')" :class="buttonVariants({
                            'variant' : 'default'
                            })">
                                <Plus class="h-4 w-4" />
                                <span>{{ $t('Create Sheet') }}</span>
                            </a>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <VTable v-model="tableData" :columns-defs="columns" />
                    </CardContent>
                    <CardFooter class="p-0">

                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
