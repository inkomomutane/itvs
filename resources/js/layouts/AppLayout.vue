<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Toaster } from '@/components/ui/sonner'
import { toast } from 'vue-sonner'
import { h,watch,ref } from 'vue'
import { AlertDto } from '@/types/generated';
import { usePage } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});


const page  = usePage();

watch(() => page.props.messages, (value) => {
    if (value) {
        toast(value?.message ?? '',{
            description: '',
            action: {
                label: 'Close',
                onClick: () => {
                    toast.dismiss();
                },
            }
        });
    }
});

</script>

<template>
    <Toaster />
    <AppLayout  :breadcrumbs="props.breadcrumbs">
        <slot />
    </AppLayout>
</template>
