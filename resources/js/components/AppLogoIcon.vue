<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import logo from "@/images/food.svg"

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img :src="logo" :class="className" v-bind="$attrs" alt="logo">
</template>
