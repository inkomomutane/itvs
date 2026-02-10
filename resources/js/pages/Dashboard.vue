<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
import  recipes from "@/images/recipes.svg"
import  food from "@/images/food.svg"
import  workers from "@/images/workers.svg"
import  chefs from "@/images/chefs.svg"
import  confirmations from "@/images/confirmations.svg"
import reports  from "@/images/reports.svg"
import DashboardLinkCard from '@/components/DashboardLinkCard.vue';
import MSelect from '@/components/MSelect.vue';
import DatePicker from '@/components/DatePicker/DatePicker.vue';
defineProps<{
    name?: string;
}>();

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];
</script>

<template>
    <Head :title="$t('Dashboard')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 mt-16 px-12 xl:px-8 max-w-7xl mx-auto">
            <div class="grid sm:grid-cols-2  md:grid-cols-3  lg:grid-cols-4 gap-8 xl:gap-8 ">
                <DashboardLinkCard :url="route('worker-meals-unconfirmed')" :image-url="food" :title="$t('Order meal')" />
                <DashboardLinkCard url="" :image-url="confirmations" :title="$t('Meal confirmations')" />
                <DashboardLinkCard :url="route('list-recipes')" :image-url="recipes" :title="$t('Menu registration')" :slug="t('Record')" />
                <DashboardLinkCard :url="route('list-employees')" :image-url="workers" :title="$t('Employees')" :slug="t('Record')" />
                <DashboardLinkCard :url="route('list-chefs')" :image-url="chefs" :title="$t('Chefs')" :slug="t('Record')" />
                <DashboardLinkCard url="" :image-url="reports" :title="$t('Meal reports')" />
            </div>
        </div>
    </AppLayout>
</template>

