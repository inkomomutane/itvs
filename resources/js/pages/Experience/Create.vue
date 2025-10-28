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
import { ExperienceDto, KeyValueDto } from '@/types/generated';
import { useForm } from '@inertiajs/vue3'
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const props = defineProps({
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
    employment_types: {
        type: Array<KeyValueDto>,
        default: [],
    }
});

const form = useForm<ExperienceDto>({
    title: '',
    employment_type: '',
    company: '',
    start_date: null,
    end_date: null,
    location: null,
    description: null,
    id: null,
});

const submit =  () => {
    form.post(route('profile.experience.store',), {
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
                <DialogTitle> {{$t('Add experience')}}</DialogTitle>
            </DialogHeader>
            <div>
                <form @submit.prevent="submit">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="title" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Title')}}
                            </Label>
                            <Input
                                id="title"
                                type="text"
                                v-model="form.title"
                                class="w-full"
                                :placeholder="$t('Title')"
                            />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="company" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Company')}}
                            </Label>
                            <Input
                                id="company"
                                type="text"
                                v-model="form.company"
                                class="w-full"
                                :placeholder="$t('Company')"
                            />
                            <InputError :message="form.errors.company" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="employment_type" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Employment type')}}
                            </Label>
                            <Select v-model="form.employment_type">
                                <SelectTrigger class="w-(--radix-dropdown-menu-trigger-width)">
                                    <SelectValue placeholder="Select" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem :value="status.key" v-for="status in props.employment_types">
                                            {{ status.value }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.employment_type" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="start_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Start date')}}
                            </Label>
                            <VueDatePicker :placeholder="$t('Start date')" format="dd/MM/yyyy" auto-apply v-model="form.start_date"/>

                            <InputError :message="form.errors.start_date" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="end_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('End date')}}
                            </Label>
                            <VueDatePicker :placeholder="$t('End date')" format="dd/MM/yyyy" auto-apply v-model="form.end_date"/>
                            <InputError :message="form.errors.end_date" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="location" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Location')}}
                            </Label>
                            <Input
                                id="location"
                                type="text"
                                v-model="form.location"
                                class="w-full"
                                :placeholder="$t('Location')"
                            />
                            <InputError :message="form.errors.location" class="mt-2" />
                        </div>

                        <div class="grid w-full  items-center gap-1.5 md:col-span-2" >
                            <Label for="description" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Description')}}
                            </Label>
                            <Textarea
                                id="description"
                                type="text"
                                rows="4"
                                v-model="form.description"
                                class="w-full"
                                :placeholder="$t('Description')"
                            />
                            <InputError :message="form.errors.description" class="mt-2" />
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
                    {{$t('Save')}}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
