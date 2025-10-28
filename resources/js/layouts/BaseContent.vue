<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { t} from '@/lib/utils';
import { Card } from '@/components/ui/card';
import {
    NavigationMenu, NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle
} from '@/components/ui/navigation-menu';
import { computed, ref } from 'vue';
import { GraduationCap, User,BriefcaseBusiness  } from 'lucide-vue-next';


const props = defineProps({
    title: {
        type: String,
        required: true,
    },
})


const page = usePage();

const isCurrentRoute = computed(() => (routeName: string) => route().current(routeName ?? ''));

const activeItemStyles = computed(() => (routeName: string) => (isCurrentRoute.value(routeName) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);

</script>

<template>
    <Card class="px-4 pt-6 bg-white dark:bg-zinc-950  mt-0  rounded-none shadow-none">
        <Heading class="mb-2 pb-0" :title="title" :description="`${title}&nbsp;`+ $t('settings management')" />
    </Card>
    <section class="">
        <slot />
    </section>
</template>
