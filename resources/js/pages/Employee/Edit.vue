<script setup lang="ts">

import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader, DialogScrollContent, DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { ExperienceDto, KeyValueDto, MaritalStatus, PatentData, Sex, UserDto } from '@/types/generated';
import { useForm } from '@inertiajs/vue3'
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { PropType } from 'vue';
import { Switch } from '@/components/ui/switch';

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
    },
    employee: {
        type:  Object as PropType<UserDto>,
        default: []
    }
});

const form = useForm<UserDto>({
    name: props.employee.name,
    sap_number: props.employee.sap_number,
    company: props.employee.company,
    department: props.employee.department,
     active: props.employee.active,
});

const submit =  () => {
    form.post(route('update-employee',{
        employee: props.employee.id
    }), {
        preserveState: true,
        onSuccess: () => {
            props.close();
        }
    })
}

</script>
<template>
    <Dialog  @update:open="props.close"  :open="props.openModal">
        <DialogScrollContent class="max-w-3xl" >
            <DialogHeader>
                <DialogTitle> {{$t('Editar')}}</DialogTitle>
            </DialogHeader>
            <div>
                <form @submit.prevent="submit">
                    <div class="grid sm:grid-cols-2  gap-4">
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
                        <separator class="md:col-span-2 mb-2"/>
                    </div>
                </form>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{$t('Cancelar')}}
                    </Button>
                </DialogClose>
                <Button  @click="submit">
                    {{$t('Actualizar')}}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
