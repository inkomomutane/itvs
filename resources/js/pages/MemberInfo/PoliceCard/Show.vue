<script setup lang="ts">
  import AppLayout from '@/layouts/AppLayout.vue';
  import MemberRouteContent from '@/pages/MemberInfo/MemberRouteContent.vue';
  import { Head } from '@inertiajs/vue3';
  import PolicemanIdCard from '@/components/PolicemanIdCard.vue';
  import { UserDto } from '@/types/generated';

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
        <MemberRouteContent :member="member" :title="$t('Member card')">
            <PolicemanIdCard :member="member"/>
        </MemberRouteContent>
    </AppLayout>
</template>
