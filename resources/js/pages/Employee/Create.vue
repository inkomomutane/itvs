<script setup lang="ts">

import {
    Dialog,
    DialogClose,
    DialogFooter,
    DialogHeader, DialogScrollContent, DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import {  UserDto } from '@/types/generated';
import { useForm } from '@inertiajs/vue3'
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/InputError.vue';
import { Switch } from '@/components/ui/switch'

const props = defineProps({
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
    roles: {
        type:  Array<RoleDto>,
        default: []
    }
});

const form = useForm<UserDto>({
    name: '',
    sap_number: '',
    password: '',
    company: '',
    department: '',
    active: true,
});

const submit =  () => {
    form.post(route('store-employee',), {
        preserveState: true,
        onSuccess: () => {
            props.close();
        }
    })
}

</script>
<template>
    <Dialog  @update:open="props.close"  :open="props.openModal">
        <DialogScrollContent class="max-w-5xl" >
            <DialogHeader>
                <DialogTitle> {{$t('Novo')}}</DialogTitle>
            </DialogHeader>
            <div>
                <form @submit.prevent="submit">
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">

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
                            <Label for="sap_number" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Email')}}
                            </Label>
                            <Input
                                id="sap_number"
                                type="text"
                                v-model="form.sap_number"
                                class="w-full"
                                :placeholder="$t('Número de SAP')"
                                required
                            />
                            <InputError :message="form.errors.sap_number" class="mt-2" />
                        </div>
                        <div class="grid w-full max-w-sm items-center gap-1.5 ">
                            <Label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Password')}}
                            </Label>
                            <Input
                                id="password"
                                type="password"
                                v-model="form.password"
                                class="w-full"
                                :placeholder="$t('Password')"
                                required
                            />
                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Empresa')}}
                            </Label>
                            <Input
                                id="company"
                                type="text"
                                v-model="form.company"
                                class="w-full"
                                :placeholder="$t('Empresa')"
                            />
                            <InputError :message="form.errors.company" class="mt-2" />
                        </div>
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="sap_number" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Departamento')}}
                            </Label>
                            <Input
                                id="department"
                                type="text"
                                v-model="form.department"
                                class="w-full"
                                :placeholder="$t('Departamento')"
                                required
                            />
                            <InputError :message="form.errors.department" class="mt-2" />
                        </div>
                        <div class="flex items-center  mt-6 space-x-2">
                            <Switch id="airplane-mode" v-model="form.active" />
                            <Label for="airplane-mode">Activo</Label>
                        </div>

                        <separator class="md:col-span-3 mb-2"/>
                    </div>
                </form>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{$t('Cancel')}}
                    </Button>
                </DialogClose>
                <Button  @click="submit">
                    {{$t('Save')}}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
