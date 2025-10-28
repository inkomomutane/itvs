<script setup lang="ts">
 import { Separator } from '@/components/ui/separator';
 import { BriefcaseBusiness,ExternalLinkIcon } from 'lucide-vue-next';
 interface Props {
     title?: string;
     subtitle?: string;
     duration?: string;
     location?: string;
     link?: {
         name?: string,
         url?: string,
     },
     content?: string;
     additional?: {
         title?: string,
         content?: string,
     },
     additional_2?: {
         title?: string,
         content?: string,
     },
     additional_3?: {
         title?: string,
         content?: string,
     },
     additional_4?: {
         title?: string,
         content?: string,
     }
 }
 const { props } = defineProps<Props>();

</script>
<template>
    <div class="p-2">
        <div class="flex mb-2">
            <div class=" h-fit w-fit bg-gray-100 dark:bg-[#0a0a0a] flex justify-center items-center">
               <div class="p-4">
                   <BriefcaseBusiness class="w-8 h-8 text-gray-500"/>
               </div>
            </div>
            <div class="grow mx-4 space-y-0.5" >
                 <h1 class="font-semibold tracking-tight first-letter:uppercase text-gray-700 dark:text-gray-800" v-if="title">
                     {{ title }}
                 </h1>
                <h2 class="text-sm font-light first-letter:uppercase">
                    {{ subtitle }}
                </h2>
                <h3 class="font-medium text-xs first-letter:uppercase text-gray-600">
                    {{ duration }}
                </h3>
                <p class="text-sm">
                    {{ location }}
                </p>
                <a :href="link.url" target="_link" class="flex items-center text-sm font-semibold  hover:text-blue-600 dark:hover:text-blue-600" v-if="link?.url">
                    <span class="mr-1" v-if="link?.name">
                        {{ link.name }}
                    </span>
                    <ExternalLinkIcon class="w-3.5 h-3.5"/>
                </a>
                <p class="text-sm my-8 first-letter:uppercase" v-if="content">
                    {{ content }}
                </p>

                <div class="flex" v-if="additional?.title">
                    <strong class="font-semibold text-sm  text-gray-800 "> {{ additional.title}}</strong>
                    <p class="text-sm" v-if="additional.content">
                        &nbsp; {{ additional.content }}
                    </p>
                </div>
            </div>
            <div>
                <slot name="actions"/>
            </div>
        </div>
    </div>
</template>
