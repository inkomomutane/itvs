<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Icon } from "@iconify/vue";
import {cn} from "@/lib/utils"

interface Props {
    from?: number;
    to?: number;
    total: number;
    next_page_url?: string;
    prev_page_url?: string;
    first_page_url: string;
    last_page_url: string;
    links: {
        url: string;
        label: string;
        active: boolean;
    }[];
}

const props = defineProps<Props>();
</script>

<template>
    <nav
        aria-label="Table navigation"
        :class="cn('flex flex-col items-start justify-between space-y-3  md:flex-row md:items-center md:space-y-0 w-full',$attrs.class)"
    >
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
            {{ $t("Showing") }}
            <span class="font-semibold text-gray-900 dark:text-white">{{
                    `${from ?? 0}-${to ?? 0}`
                }}</span>
            {{ $t("of") }}
            <span class="font-semibold text-gray-900 dark:text-white">
                {{ total }}</span
            >
        </span>
        <ul class="inline-flex items-stretch gap-1 text-sm font-semibold">
            <li>
                <Link
                    class="flex h-9 w-9 items-center justify-center rounded hover:border"
                    :href="first_page_url"
                >
                    <Icon icon="radix-icons:double-arrow-left" />
                </Link>
            </li>
            <li>
                <Link
                    class="flex h-9 w-9 items-center justify-center rounded hover:border"
                    :href="prev_page_url"
                >
                    <Icon icon="radix-icons:chevron-left" />
                </Link>
            </li>

            <li v-for="link in links.slice(1, -1)" :key="link.label">
                <Link
                    :href="link.url ?? ''"
                    :class="
                        cn(
                            'flex h-9 w-9 items-center justify-center rounded-md border border-gray-200 font-medium dark:border-gray-600',
                            {
                                'bg-slate-950 text-white dark:bg-white dark:text-gray-950':
                                    link.active,
                            },
                        )
                    "
                >
                    {{ link.label }}
                </Link>
            </li>
            <li>
                <Link
                    class="flex h-9 w-9 items-center justify-center rounded hover:border"
                    :href="next_page_url"
                >
                    <Icon icon="radix-icons:chevron-right" />
                </Link>
            </li>
            <li>
                <Link
                    class="flex h-9 w-9 items-center justify-center rounded hover:border"
                    :href="last_page_url"
                >
                    <Icon icon="radix-icons:double-arrow-right" />
                </Link>
            </li>
        </ul>
    </nav>
</template>
