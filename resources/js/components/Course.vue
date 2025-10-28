<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import { format } from 'date-fns';
import { pt } from 'date-fns/locale/pt'
import { Rss,GraduationCap ,  } from "lucide-vue-next"
import { cn } from '@/lib/utils';
import { ref } from 'vue';
import { EducationDto } from '@/types/generated';

interface Props {
    education: EducationDto,
    class?: string,
}

const props = defineProps<Props>()
const parentDiv = ref<HTMLElement | null>(null);

</script>
<template>
    <Separator/>
    <div :class="cn('group sm:p-2 py-4  tracking-wide transition-all duration-200  py-1  dark:my-0 hover:bg-black/5  dark:hover:bg-white/5  bg-white dark:bg-transparent cursor-pointer',props.class)" >
        <div  class="flex justify-between" ref="parentDiv" >
            <div class="inline-block self-center text-[8px] text-foreground uppercase -rotate-180 [writing-mode:vertical-lr]  underline underline-offset-4 decoration-1 pr-1 font-medium decoration-emerald-600  " >
                {{ $t('Education') }}
            </div>
            <div class="grow flex flex-col mx-3 space-y-4">
                 <div class="space-y-1">
                     <h4 id="name" class="text-gray-700 dark:text-gray-50 font-semibold   leading-[100%]]  tracking-tight ">
                         {{ education.school ??'' }}
                     </h4>
                     <p  class="text-gray-900 dark:text-gray-400 font-light text-xs">
                         <span class="font-medium"> {{ education.degree ??'' }} </span>,&nbsp;{{ education.field_of_study ??'' }}
                     </p>
                     <p>
                            <span class="text-xs"> {{$t('Grade')}} : {{ education.grade ??'' }} </span>
                     </p>

                     <p id="job" class="text-gray-800 my-2 text-sm dark:text-gray-100  line-clamp-3">
                         <span class="font-mono tracking-tight" v-html="education.description"></span>
                     </p>
                 </div>
                <div class="text-xs text-italic font-medium flex items-center space-x-2">
                    <span class="font-medium uppercase   "> {{  format((new Date(education.start_date)),"dd MMM yyyy",{ locale: pt})  }}</span>
                       <span>
                           -
                       </span>
                    <span class="font-medium uppercase   "> {{  format((new Date(education.end_date)),"dd MMM yyyy",{ locale: pt})  }}</span>
                    <Separator orientation="vertical" class="w-px" />
                </div>
            </div>
            <div class="hidden p-4 flex-none  lg:flex items-start justify-center  h-fit    outline-white/25 " :class="cn('bg-gray-200 dark:bg-emerald-100/5')">
                <GraduationCap class="w-10 h-10 text-zinc-800/90  dark:text-zinc-500" />
               </div>
        </div>
    </div>
    <Separator/>
</template>
