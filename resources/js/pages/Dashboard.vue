<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
import { EllipsisVertical, Search, TrendingDown } from 'lucide-vue-next';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle
} from '@/components/ui/card';
import CardChart from '@/pages/Dashboard/CardChart.vue';
import { h, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import VTable from '@/components/VTable/VTable.vue';
import Pagination from '@/components/Pagination.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import { MealDto, UserDto } from '@/types/generated';
import VHeader from '@/components/VTable/VHeader.vue';
import VCell from '@/components/VTable/VCell.vue';
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    parseAbsolute,
    today,
    DateValue,
    CalendarDate
} from '@internationalized/date';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem, DropdownMenuSeparator,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { formatDate } from '@/helpers';
const props = defineProps<{
    name?: string;
    latestMeals: any;
}>();

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];

const tableData = ref();
watch(
    () => props.latestMeals?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

const columnHelper = createColumnHelper<MealDto>();
const columns = [
    columnHelper.accessor('worker_name', {
        header: ({ column }) => h(VHeader, { column, title: t('Funcionário') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    columnHelper.accessor('sap_number', {
        header: ({ column }) => h(VHeader, { column, title: t('Número de sap') }),
        cell: (info) => h(VCell, { cell: info, value: info.getValue() }),
    }),
    //
    columnHelper.accessor('meal_date', {
        header: ({ column }) => h(VHeader, { column, title: t('Data') }),
        cell: (info) => h(VCell, { cell: info, value:  formatDate(parseAbsolute(info.getValue())) }),
    }),
    columnHelper.accessor('period', {
        header: ({ column }) => h(VHeader, { column, title: t('Refeição') }),
        cell: (info) => h(VCell, { cell: info, value: t(info.getValue()) }),
    }),
    columnHelper.accessor('recipe_name', {
        header: ({ column }) => h(VHeader, { column, title: t('Opção') }),
        cell: (info) => h(VCell, { cell: info, value: t( info.getValue() )}),
    }),
    columnHelper.accessor('status', {
        header: ({ column }) => h(VHeader, { column, title: t('Estado') }),
        cell: (info) => h(VCell, { cell: info, value: t(info.getValue()) }),
    }),
];



onMounted(() => {
    axios.get(route('meal-list'))
        .then(response => {
            latestMeals.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching latest meals:', error);
        })
        .finally(() => {
            loadingMeals.value = false;
        });
})
</script>

<template>
    <Head :title="$t('Dashboard')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 mt-16 px-12 xl:px-8  w-full mx-auto">
            <div class="grid sm:grid-cols-2  md:grid-cols-3  lg:grid-cols-4 gap-8 xl:gap-8 ">
                <CardChart title="Funcionários" footer_title="Hoje" :route="route('today-employee-count')" />
                <CardChart title="Utilizadores do sistema" footer_title="Hoje" :route="route('today-users-count')" />
                <CardChart title="Reservas" footer_title="Hoje" :route="route('today-meals-count')" />
                <CardChart title="Consumidos" footer_title="Hoje" :route="route('today-eaten-meals-count')" />
            </div>
            <Card class="">
                <CardHeader class="flex flex-row items-center justify-between p-4">
                  <div>
                      <CardTitle>{{ $t('Reservas') }}</CardTitle>
                      <CardDescription>{{ $t('Últimas reservas de refeições') }}</CardDescription>
                  </div>
                    <div>
                         <Button as="a" :href="route('export-meal-report')" variant="outline" size="sm">
                            {{ 'Exportar' }}
                        </Button>
                    </div>
                </CardHeader>
                <CardContent class="p-4  pb-0">
                    <VTable v-model="tableData" :columns-defs="columns" :pinning="{
                            right: ['actions']
                        }" />
                </CardContent>
                <CardFooter class="p-0">
                    <Pagination
                        :from="latestMeals?.from"
                        :to="latestMeals.to"
                        :total="latestMeals.total"
                        :links="latestMeals?.links"
                        :first_page_url="latestMeals?.first_page_url"
                        :last_page_url="latestMeals?.last_page_url"
                        :next_page_url="latestMeals.next_page_url"
                        :prev_page_url="latestMeals?.prev_page_url"
                        class="p-4"
                    />
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>

