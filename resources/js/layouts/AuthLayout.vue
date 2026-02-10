<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthCardLayout.vue';
import { t } from '@/lib/utils';
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="t(title)" :description="t(description)">
        <slot />
    </AuthLayout>
</template>
