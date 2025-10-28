<script setup lang="ts">
import BaseContent from '@/layouts/BaseContent.vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { KeyValueDto, MaritalStatus, PatentData, Sex, UserDto } from '@/types/generated';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import MemberRouteContent from '@/pages/MemberInfo/MemberRouteContent.vue';

const props = defineProps<{
     member: UserDto,
    sex: Array<KeyValueDto>,
    marital_status : Array<KeyValueDto>,
    patents: Array<PatentData>
}>();


const form = useForm<UserDto>(props.member);

const updateProfile = () => form.post(route('member.update-base-info',{
    member: props.member.id
}));


</script>

<template>
    <Head :title="$t('Member')" />
    <AppLayout>
        <MemberRouteContent :member="member" :title="$t('Member')">
            <Card class="rounded-sm shadow-none">
                <CardHeader class="flex flex-col items-center justify-end space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
                    <div class="flex flex-col items-center justify-end   md:flex-row w-full ">
                        <Button @click="updateProfile" class="w-full md:w-auto">
                            {{$t('Member')}}
                        </Button>
                    </div>
                </CardHeader>
                <CardContent class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 ">
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Name')}}
                        </Label>
                        <Input
                            id="name"
                            type="text"
                            v-model="form.name"
                            class="w-full"
                            :placeholder="$t('Enter your name')"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="second_name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Second name')}}
                        </Label>
                        <Input
                            id="second_name"
                            type="text"
                            v-model="form.second_name"
                            class="w-full"
                            :placeholder="$t('Second name')"
                        />
                        <InputError :message="form.errors.second_name" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="last_name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Last name')}}
                        </Label>
                        <Input
                            id="last_name"
                            type="text"
                            v-model="form.last_name"
                            class="w-full"
                            :placeholder="$t('Last name')"
                        />
                        <InputError :message="form.errors.last_name" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="sex" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Patent') }}
                        </Label>
                        <Select v-model="form.patent_id">
                            <SelectTrigger class="w-(--radix-dropdown-menu-trigger-width)">
                                <SelectValue placeholder="Patente" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="patent.id" v-for="patent in props.patents">
                                        {{ patent.title }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.sex" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="ingress_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Ingress data')}}
                        </Label>
                        <VueDatePicker format="dd/MM/yyyy" auto-apply v-model="form.ingress_date"></VueDatePicker>
                        <InputError :message="form.errors.ingress_date" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Language')}}
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="w-full"
                            :placeholder="$t('email')"
                            required
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="language" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Language')}}
                        </Label>
                        <Input
                            id="language"
                            type="text"
                            v-model="form.language"
                            class="w-full"
                            :placeholder="$t('Language')"
                        />
                        <InputError :message="form.errors.language" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="sex" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Sex') }}
                        </Label>
                        <Select v-model="form.sex">
                            <SelectTrigger class="w-(--radix-dropdown-menu-trigger-width)">
                                <SelectValue placeholder="Select" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="sexOption.key" v-for="sexOption in props.sex">
                                        {{ sexOption.value }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.sex" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="marital_status" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Marital status') }}
                        </Label>
                        <Select v-model="form.marital_status">
                            <SelectTrigger class="w-(--radix-dropdown-menu-trigger-width)">
                                <SelectValue placeholder="Select" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="status.key" v-for="status in props.marital_status">
                                        {{ status.value }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.marital_status" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="nationality" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Nationality')}}
                        </Label>
                        <Input
                            id="nationality"
                            type="text"
                            v-model="form.nationality"
                            class="w-full"
                            :placeholder="$t('Nationality')"
                        />
                        <InputError :message="form.errors.nationality" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="place_of_birth" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Place of birth')}}
                        </Label>
                        <Input
                            id="place_of_birth"
                            type="text"
                            v-model="form.place_of_birth"
                            class="w-full"
                            :placeholder="$t('Place of birth')"
                        />
                        <InputError :message="form.errors.place_of_birth" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="birth_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Birth date')}}
                        </Label>
                        <VueDatePicker format="dd/MM/yyyy" auto-apply v-model="form.birth_date"></VueDatePicker>
                        <InputError :message="form.errors.birth_date" class="mt-2" />
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="id_number" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('ID Number')}}
                        </Label>
                        <Input
                            id="id_number"
                            type="text"
                            v-model="form.id_number"
                            class="w-full"
                            :placeholder="$t('ID Number')"
                        />
                        <InputError :message="form.errors.id_number" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="id_emitted_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('ID Emitted At')}}
                        </Label>
                        <VueDatePicker :placeholder="$t('ID Emitted At')" format="dd/MM/yyyy" auto-apply v-model="form.id_emitted_at"></VueDatePicker>
                        <InputError :message="form.errors.id_emitted_at" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="id_expires_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('ID expires at')}}
                        </Label>
                        <VueDatePicker :placeholder="$t('ID expires at')" format="dd/MM/yyyy" auto-apply v-model="form.id_expires_at"></VueDatePicker>
                        <InputError :message="form.errors.id_expires_at" class="mt-2" />
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="passport_number" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Passport Number')}}
                        </Label>
                        <Input
                            id="passport_number"
                            type="text"
                            v-model="form.passport_number"
                            class="w-full"
                            :placeholder="$t('Passport Number')"
                        />
                        <InputError :message="form.errors.passport_number" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="passport_emitted_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Passport Emitted At')}}
                        </Label>
                        <VueDatePicker :placeholder="$t('Passport Emitted At')" format="dd/MM/yyyy" auto-apply v-model="form.passport_emitted_at"></VueDatePicker>
                        <InputError :message="form.errors.passport_emitted_at" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="passport_expires_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Passport expires at')}}
                        </Label>
                        <VueDatePicker :placeholder="$t('Passport expires at')" format="dd/MM/yyyy" auto-apply v-model="form.passport_expires_at"></VueDatePicker>
                        <InputError :message="form.errors.passport_expires_at" class="mt-2" />
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="driver_licence" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Driver license')}}
                        </Label>
                        <Input
                            id="driver_licence"
                            type="text"
                            v-model="form.driver_licence"
                            class="w-full"
                            :placeholder="$t('Driver license')"
                        />
                        <InputError :message="form.errors.driver_licence" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="driver_licence_emitted_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Driver license emitted At')}}
                        </Label>
                        <VueDatePicker :placeholder="$t('Driver license Emitted At')" format="dd/MM/yyyy" auto-apply v-model="form.driver_licence_emitted_at"></VueDatePicker>
                        <InputError :message="form.errors.driver_licence_emitted_at" class="mt-2" />
                    </div>
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="driver_licence_expires_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Driver license expires at')}}
                        </Label>
                        <VueDatePicker :placeholder="$t('License expires at')" format="dd/MM/yyyy" auto-apply v-model="form.driver_licence_expires_at"></VueDatePicker>
                        <InputError :message="form.errors.driver_licence_expires_at" class="mt-2" />
                    </div>
                </CardContent>
            </Card>
        </MemberRouteContent>
    </AppLayout>
</template>
