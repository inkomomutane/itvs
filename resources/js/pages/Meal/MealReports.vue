<script setup lang="ts">
import { NUMBER, t } from '@/lib/utils';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle
} from '@/components/ui/card';
import { Search, TrendingDown, TrendingUp } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import VTable from '@/components/VTable/VTable.vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import { h, ref, watch } from 'vue';
import { createColumnHelper } from '@tanstack/vue-table';
import { KeyValueDto, MealDto } from '@/types/generated';
import VHeader from '@/components/VTable/VHeader.vue';
import VCell from '@/components/VTable/VCell.vue';
import { formatDate } from '@/helpers';
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    parseAbsolute,
    today,
    DateValue,
    CalendarDate
} from '@internationalized/date';
import MSelect from '@/components/MSelect.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

const props = defineProps({
    meals: {
        type: Object,
        required: true
    },
    periods: {
        type: Array<KeyValueDto>,
        default: () => []
    },
        meal_statuses: {
            type: Array<KeyValueDto>,
            default: () => []
        },
    users: {
        type: Array,
        default: () => []
    }
})

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
    {
        title: t('Relatórios de refeições'),
        href: route('list-meals-report'),
    },
];

const tableData = ref();
watch(
    () => props.meals?.data,
    (newData) => {
        tableData.value = newData ?? [];
    },
    { immediate: true, deep: true },
);

//get search query from url and set it to searchQuery
// fill the search query with the url query params
const urlSearchParams = new URLSearchParams(window.location.search);
const searchQuery = ref({
    worker_id: urlSearchParams.get('worker_id') ?? null,
    period: urlSearchParams.get('period') ?? null,
    status: urlSearchParams.get('status') ?? null,
    search: urlSearchParams.get('search') ?? null,
})

watch(searchQuery, (value) => {
    router.visit(
        route('list-meals-report', {
            search: value.search ?? '',
            worker_id: value.worker_id ?? null,
            period: value.period ?? null,
            status: value.status ?? null,
        }),
        {
            only: [
                'meals',
                'periods',
                'meal_statuses',
                'users',
            ],
            replace: true,
            preserveState: true,
        },
    );
},{
    deep: true
});



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

</script>
<template>
    <Head :title="t('Refeições')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 mt-16 px-12 xl:px-8  w-full mx-auto">
            <div class="grid sm:grid-cols-2  md:grid-cols-3  lg:grid-cols-4 gap-8 xl:gap-8 ">
                <Card class="border-none shadow-none h-fit">
                    <CardHeader>
                        <CardDescription>
                            {{ 'Total' }}
                        </CardDescription>
                        <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                            {{ NUMBER( 0,0).format() }}
                        </CardTitle>
                        <CardAction>
                            <Badge variant="outline">

                                <TrendingUp v-if="1 && 1 > 0" />
                                <TrendingDown v-else />
                                {{  1 > 0 ? '+' : '' }}{{ NUMBER(0,2).format() }}%
                            </Badge>
                        </CardAction>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1.5 text-sm">
                        <div class="line-clamp-1 flex gap-2 mt-2 flex gap-x-4 text-xs text-muted-foreground">
                            {{ 'Leading' }}
                            <TrendingUp  class="size-4" v-if="1 && 1 > 0" />
                            <TrendingDown class="size-4"  v-else />
                        </div>
                    </CardFooter>
                </Card>
                <Card class="border-none shadow-none h-fit">
                    <CardHeader>
                        <CardDescription>
                            {{ 'Total' }}
                        </CardDescription>
                        <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                            {{ NUMBER( 0,0).format() }}
                        </CardTitle>
                        <CardAction>
                            <Badge variant="outline">

                                <TrendingUp v-if="1 && 1 > 0" />
                                <TrendingDown v-else />
                                {{  1 > 0 ? '+' : '' }}{{ NUMBER(0,2).format() }}%
                            </Badge>
                        </CardAction>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1.5 text-sm">
                        <div class="line-clamp-1 flex gap-2 mt-2 flex gap-x-4 text-xs text-muted-foreground">
                            {{ 'Leading' }}
                            <TrendingUp  class="size-4" v-if="1 && 1 > 0" />
                            <TrendingDown class="size-4"  v-else />
                        </div>
                    </CardFooter>
                </Card>
                <Card class="border-none shadow-none h-fit">
                    <CardHeader>
                        <CardDescription>
                            {{ 'Total' }}
                        </CardDescription>
                        <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                            {{ NUMBER( 0,0).format() }}
                        </CardTitle>
                        <CardAction>
                            <Badge variant="outline">

                                <TrendingUp v-if="1 && 1 > 0" />
                                <TrendingDown v-else />
                                {{  1 > 0 ? '+' : '' }}{{ NUMBER(0,2).format() }}%
                            </Badge>
                        </CardAction>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1.5 text-sm">
                        <div class="line-clamp-1 flex gap-2 mt-2 flex gap-x-4 text-xs text-muted-foreground">
                            {{ 'Leading' }}
                            <TrendingUp  class="size-4" v-if="1 && 1 > 0" />
                            <TrendingDown class="size-4"  v-else />
                        </div>
                    </CardFooter>
                </Card>
                <Card class="border-none shadow-none h-fit">
                    <CardHeader>
                        <CardDescription>
                            {{ 'Total' }}
                        </CardDescription>
                        <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                            {{ NUMBER( 0,0).format() }}
                        </CardTitle>
                        <CardAction>
                            <Badge variant="outline">

                                <TrendingUp v-if="1 && 1 > 0" />
                                <TrendingDown v-else />
                                {{  1 > 0 ? '+' : '' }}{{ NUMBER(0,2).format() }}%
                            </Badge>
                        </CardAction>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1.5 text-sm">
                        <div class="line-clamp-1 flex gap-2 mt-2 flex gap-x-4 text-xs text-muted-foreground">
                            {{ 'Leading' }}
                            <TrendingUp  class="size-4" v-if="1 && 1 > 0" />
                            <TrendingDown class="size-4"  v-else />
                        </div>
                    </CardFooter>
                </Card>
            </div>
            <Card class="border-none shadow-none h-fit">
                <CardHeader class="flex flex-col md:flex-row items-start justify-between p-4">
                    <div class="flex flex-col gap-y-4">
                        <CardTitle>{{ $t('Refeições') }}</CardTitle>
                        <div class="flex flex-col md:flex-row items-star gap-4">
                            <div>
                                <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $t('Pesquisa') }}
                                </Label>
                                <div class="relative w-full max-w-sm items-center">
                                    <Input v-model="searchQuery.search" id="search" type="text" :placeholder="t('Search') + '...'" class="pl-10" />
                                    <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                                <Search class="size-4 text-muted-foreground" />
                            </span>
                                </div>
                            </div>
                            <div>
                                <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $t('Funcionário') }}
                                </Label>
                                <MSelect
                                    :options="props.users"
                                    v-model="searchQuery.worker_id"
                                    :reduce="(e) => e.id"
                                    :get-label="(e) => t(e.name)"
                                    :placeholder="$t('Funcionário')"
                                />
                            </div>
                            <div>
                                <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $t('Period') }}
                                </Label>
                                <MSelect
                                    :options="props.periods"
                                    v-model="searchQuery.period"
                                    :reduce="(e) => e.key"
                                    :get-label="(e) => t(e.value)"
                                    :placeholder="$t('Period')"
                                />
                            </div>
                            <div>
                                <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $t('Estado da refeição') }}
                                </Label>
                                <MSelect
                                    :options="props.meal_statuses"
                                    v-model="searchQuery.status"
                                    :reduce="(e) => e.key"
                                    :get-label="(e) => t(e.value)"
                                    :placeholder="$t('Estado da refeição')"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
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
                        :from="meals?.from"
                        :to="meals.to"
                        :total="meals.total"
                        :links="meals?.links"
                        :first_page_url="meals?.first_page_url"
                        :last_page_url="meals?.last_page_url"
                        :next_page_url="meals.next_page_url"
                        :prev_page_url="meals?.prev_page_url"
                        class="p-4"
                    />
                </CardFooter>
            </Card>
        </div>

    </AppLayout>
</template>
