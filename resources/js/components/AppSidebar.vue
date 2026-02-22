<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { BookOpen, Cookie, Folder, LayoutGrid, Users, Utensils } from 'lucide-vue-next';

import { Avatar, AvatarFallback } from '@/components/ui/avatar';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: route('dashboard'),
        route: 'dashboard',
        icon: LayoutGrid,

    },
    {
        title: 'Utilizadores do sistema',
        url: route('list-employees'),
        route : 'list-employees',
        icon: Users,
    },
    {
        title: 'Relatórios de refeições',
        url: route('list-meals-report'),
        route: 'list-meals-report',
        icon: Cookie,
    },
    {
        title: 'Pratos',
        url: route('list-recipes'),
        route: 'list-recipes',
        icon: Utensils,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="sidebar">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="w-fit">
                        <a :href="route('dashboard')" >
                            <Avatar class="rounded-md ">
                                <AvatarFallback class="bg-blue-600 rounded-md text-white font-bold">CM</AvatarFallback>
                            </Avatar>
                             <div class="flex flex-col w-fit">
                                 <div class="font-base font-bold">
                                     Refeitório
                                 </div>
                                 <div>
                                     Gestão
                                 </div>
                             </div>
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
