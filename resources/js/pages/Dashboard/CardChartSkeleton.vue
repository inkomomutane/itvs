<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
</script>

<template>
    <Card class="w-full h-full border-0 shadow-none dark:border">
        <CardHeader>
        </CardHeader>
        <CardContent class="w-full h-40 flex flex-row justify-center items-center">
            <div class="flex items-end justify-center gap-8 w-full h-40 p-8">
                <Skeleton class="h-8 w-6 mb-0 rounded" />
                <Skeleton class="h-30 w-6 mb-0 rounded" />
                <Skeleton class="h-12 w-6 mb-0 rounded" />
                <Skeleton class="h-30 w-6 mb-0 rounded" />
                <Skeleton class="h-17 w-6 mb-0 rounded" />
            </div>
        </CardContent>
    </Card>
</template>
