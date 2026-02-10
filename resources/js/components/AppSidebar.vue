<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { BookOpen, Folder, LayoutGrid, Users, Utensils } from 'lucide-vue-next';

import { Avatar, AvatarFallback } from '@/components/ui/avatar';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Utilizadores do sistema',
        url: '/dashboard/list-employees',
        icon: Users,
    },
    {
        title: 'Funcionários',
        url: route('list-employees'),
        icon: Users,
    },
    {
        title: 'Pratos',
        url: route('list-recipes'),
        icon: Utensils,
    },
    //Utensils
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
