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
    chef: {
        type:  Object as PropType<UserDto>,
        default: []
    }
});

const form = useForm<UserDto>({
    name: props.chef.name,
    email: props.chef.email,
});

const submit =  () => {
    form.post(route('update-chef',{
        user: props.chef.id
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
                <DialogTitle> {{$t('Edit chef')}}</DialogTitle>
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
                            <Label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Email')}}
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
                        <separator class="md:col-span-2 mb-2"/>
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
                    {{$t('Update')}}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
