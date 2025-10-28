<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
import  recipes from "@/images/recipes.svg"
import  food from "@/images/food.svg"
import  workers from "@/images/workers.svg"
import DashboardLinkCard from '@/components/DashboardLinkCard.vue';
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 mt-16 px-12 xl:px-0">
            <div class="grid sm:grid-cols-2  md:grid-cols-3  lg:grid-cols-4 gap-8 xl:gap-8 ">
                <DashboardLinkCard url="" :image-url="food" :title="$t('Food')" />
                <DashboardLinkCard url="" :image-url="workers" :title="$t('Workers')" />
                <DashboardLinkCard url="" :image-url="recipes" :title="$t('Recipes')" />
                <DashboardLinkCard url="" :image-url="recipes" :title="$t('Recipes')" />
            </div>
        </div>
    </AppLayout>
</template>

