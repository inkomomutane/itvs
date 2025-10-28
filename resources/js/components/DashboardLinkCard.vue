<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    url: {
        type: String,
        default: ''
    },
    imageUrl: {
        type: String,
    },
    title: {
        type: String,
        default: ''
    }
})


</script>

<template>

    <div class="rounded-lg outline outline-zinc-400/10 overflow-hidden  flex flex-col bg-white dark:bg-slate-950">

        <div class="relative">
            <Link :href="url">
            <img class="p-8"
                 :src="imageUrl"
                 alt="Sunset in the mountains">
            <div
                class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25 p-8">
            </div>
        </Link>
            <Link href="#">
                <div
                    class="text-xs absolute top-0 right-0 bg-indigo-600 px-4 py-2 text-white mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                    SGR
                </div>
            </Link>
        </div>
        <div class="px-6 py-4 mb-auto">
            <Link :href="url"
               class="font-medium text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out inline-block mb-2">
                {{ $t(title) }}</Link>
            <p class="text-gray-500 text-sm">
                {{ $t(subtitle ?? '') }}
            </p>
        </div>
    </div>
</template>
