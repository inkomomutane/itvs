<script setup lang="ts">
import logo from "@/images/logo.png"
import moz from "@/images/moz.png"
import emb from "@/images/moz_emb.png"
import avatar from "@/images/avatar.jpg"
import { PropType } from 'vue';
import { UserDto } from '@/types/generated';
import { formatDate } from 'date-fns';
import { useQRCode } from '@vueuse/integrations/useQRCode'
import { shallowRef } from 'vue'
import { useForm } from '@inertiajs/vue3';
import FileInput from '@/components/FileInput/FileInput.vue';
import {t} from "@/lib/utils"
import { Button } from '@/components/ui/button';
// `qrcode` will be a ref of data URL
const props = defineProps({
    member: {
        type: Object as PropType<UserDto>
    }
})

const text = shallowRef((props.member.email + ' - ' + props.member?.name ?? '' ) ?? 'Guile')
const qrcode = useQRCode(text)


const form  = useForm({
    logo: null,
})

const updateLogo = () => {
    form.post(route('member-upload-picture',{
        member: props.member.id
    }))
}


</script>

<template>
    <main class="bg-white dark:bg-zinc-950 flex flex-col items-center  font-segoe leading-normal py-32 border">

         <div class=" flex flex-row gap-10 my-3">
             <FileInput
                 v-model="form.logo"
                 :multiple="false"
                 accept="image/*"
                 :max-file-size="1024 * 512"
                 class="w-fit"
                 :title="t('Carregar fotografia')"
             />

             <Button @click="updateLogo">
                 {{ t('Update') }}
             </Button>
         </div>


        <!-- ID CARD -->
        <div
            class="flex flex-col gap-[10px] z-20 relative w-[500px] h-[300px] p-[15px] rounded-[10px] overflow-hidden bg-gradient-to-b from-[#7f6065] to-[#638e8d] group">
            <!-- TOP PART -->
            <div class="relative flex justify-evenly gap-[15px]">
                <div>
                    <img class="w-[60px] h-[60px] opacity-50" :src="emb" alt="logo" />
                </div>
                <div class="text-center ">
                    <p class="text-[19px] font-medium uppercase">
                         República de Moçambique
                    </p>
                    <p class="text-[14px] mt-[-5px] font-medium uppercase">
                         Cartão de Membro
                    </p>
                     <p class="text-xs">
                         <strong>NIP: &nbsp  {{ member.email }}</strong>
                     </p>
                </div>

                <div class="flex">
                    <img class=" h-[40px] opacity-50" :src="moz"  alt=""/>
                </div>
                <p class="absolute top-[52px] left-[233px] text-white opacity-20 text-[19px] font-medium">

                </p>
            </div>
            <!-- BOTTOM PART -->
            <div class="relative flex flex-1">
                <!-- ID IMAGE -->
                <div class="relative z-20 flex items-start">
                    <img :src="member.avatar ?? avatar" alt="Person" class="w-32 rounded opacity-70" />
                </div>
                <!-- ID DATA -->
                <div class="relative z-20 flex flex-col flex-1 ml-[8px]">
                    <div>
                        <span class="block text-[9px]">
                            Nome/Name
                        </span>
                        <p class="font-medium font-rubik">
                            {{ member.name ?? '' + member.first_name ?? '' + member.second_name  }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-[9px]">
                            Apelido/Surname
                        </span>
                        <p class="font-medium font-rubik">
                            {{ member.last_name ?? '' }}
                        </p>
                    </div>
                    <div class="flex justify-between gap-[15px] w-full mt-[12px]">
                        <div>
                            <span class="block text-[9px]">
                                Sexo
                            </span>
                            <span class="block text-[9px]">
                                Sex
                            </span>
                            <p class="font-medium">
                                {{ member.sex }}
                            </p>
                        </div>
                        <div>
                            <span class="block text-[9px]">
                                Nacionalidade
                            </span>
                            <span class="block text-[9px]">
                                Nationality
                            </span>
                            <p class="font-medium">
                                Moçambique
                            </p>
                        </div>
                        <div>
                            <span class="block text-[9px]">
                                Date de nascimento
                            </span>
                            <span class="block text-[9px]">
                                Date of birth
                            </span>
                            <p class="font-medium">
                                {{ formatDate(new Date(member.birth_date),'dd/MM/Y') }}
                            </p>
                            <span class="invisible block text-[9px]">
                                Invisible text
                            </span>
                            <span class="block text-[9px]">
                                Patente/Patent
                            </span>
                            <p class="text-[12px] font-bold font-cedarville mt-[5px]">
                                {{ member.patent_name ?? ''}}
                            </p>
                        </div>

                    </div>
                </div>
                <!-- ABSOLUTE ELEMENT - CIRCLE WITH IMAGE -->
                <div
                    class="absolute z-10 w-[120px] h-[120px] rounded-full bg-gray-800 opacity-10 left-[77px] top-[-4px] overflow-hidden">
                    <img alt="logo" class="" :src="logo" />
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[35px] ml-[35px]">
                        MNE
                    </p>
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[-39px] ml-[50px]">
                        MNE
                    </p>
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[-39px] ml-[65px]">
                        MNE
                    </p>
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[-39px] ml-[80px]">
                        MNE
                    </p>
                </div>
                <!-- COUNTRY CODE ABSOLUTE -->

                <div class="absolute top-[7px] left-[212px] font-rubik me-1 right-0  flex justify-end ">
                    <img alt="logo" class="w-20 rounded bg-zinc-100" :src="qrcode" />
                </div>

                <svg width="400" height="100" class="absolute top-[7px] left-[212px] font-rubik me-1">
                    <text fill="white" fill-opacity="0.1" font-size="80" x="200" y="70" text-anchor="middle"
                          stroke="#e3c5a0" stroke-opacity="0.1" class="me-1">
                        {{ $t('SGR') }}
                    </text>
                </svg>

                <!-- ABSOLUTE ELEMENT - PROFILE IMAGE -->
                <div class="absolute w-[80px] h-[55px] rounded-[100px] bg-white top-[143px] left-[182px] opacity-20">
                    <img :src="logo" alt=""
                         class="w-[47px] h-[52px] blur-[1.9px] ml-[18px] mt-[3px]" />
                </div>
            </div>
            <!-- ABSOLUTE ELEMENTS - RIGHT LINES -->
            <div class="absolute z-10 opacity-50 w-[220px] h-[300px] left-[290px]
                top-[100px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <div class="absolute z-10 opacity-30 w-[220px] h-[300px] left-[290px]
                top-[133px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <div class="absolute z-10 opacity-25 w-[220px] h-[300px] left-[290px]
                top-[164px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <div class="absolute z-10 opacity-20 w-[220px] h-[300px] left-[290px]
                top-[195px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <!-- ABSOLUTE ELEMENTS - LEFT LINES -->
            <div class="absolute z-10 opacity-50 w-[350px] h-[450px] left-[-126px]
                top-[-47px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <div class="absolute z-10 opacity-30 w-[350px] h-[450px] left-[-147px]
                top-[-39px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <div class="absolute z-10 opacity-25 w-[350px] h-[450px] left-[-168px]
                top-[-31px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <div class="absolute z-10 opacity-20 w-[350px] h-[450px] left-[-189px]
                top-[-24px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
        </div>
    </main>
</template>
