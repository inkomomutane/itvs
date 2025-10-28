<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import PolicemanIdCard from '@/components/PolicemanIdCard.vue';
import { UserDto } from '@/types/generated';
import BaseContent from '@/layouts/BaseContent.vue';

const props = defineProps({
    member: {
        type: Object as PropType<UserDto>,
        required: true,
    },
});


</script>

<template>
    <Head :title="$t('Member card')" />
    <AppLayout>
        <BaseContent :title="$t('Member card')">
            <PolicemanIdCard :member="member"/>
        </BaseContent>
    </AppLayout>
</template>
