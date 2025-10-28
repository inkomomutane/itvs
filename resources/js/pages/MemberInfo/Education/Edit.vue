<script setup lang="ts">

import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader, DialogScrollContent, DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { ClientDto, EducationDto, FormacaoAcademicaDto, ModuleDto, UserDto } from '@/types/generated';
import { useForm } from '@inertiajs/vue3';
import { PropType,ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { DownloadIcon } from "lucide-vue-next"
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import Delete from './Delete.vue';
import { crudManager } from '@/lib/utils';

const props = defineProps({
    close: {
        type: Function,
        required: true
    },
    openModal: {
        type: Boolean,
        required: true
    },
    education: {
        type: Object as PropType<EducationDto>,
        required: true
    },
    member: {
        type: Object as PropType<UserDto>,
        required: true,
    },
});

const form = useForm<ModuleDto>(props.education);

const submit = () => {
    form.put(route('member.education.update', {
        education: props.education?.id,
        member: props.member?.id,
    }), {
        preserveState: true,
        onSuccess: () => {
            props.close();
        }
    });
};

const crudDeleteManagerRef = ref(crudManager<EducationDto>());


</script>
<template>
    <Dialog @update:open="props.close" :open="props.openModal">
        <DialogScrollContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle> {{ $t('Editar formação') }}</DialogTitle>
            </DialogHeader>
            <div>
                <form @submit.prevent="submit">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="school" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('School')}}
                            </Label>
                            <Input
                                id="school"
                                type="text"
                                v-model="form.school"
                                class="w-full"
                                :placeholder="$t('School')"
                            />
                            <InputError :message="form.errors.school" class="mt-2" />
                        </div>


                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="degree" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Degree')}}
                            </Label>
                            <Input
                                id="degree"
                                type="text"
                                v-model="form.degree"
                                class="w-full"
                                :placeholder="$t('Degree')"
                            />
                            <InputError :message="form.errors.degree" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="field_of_study" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Field of study')}}
                            </Label>
                            <Input
                                id="field_of_study"
                                type="text"
                                v-model="form.field_of_study"
                                class="w-full"
                                :placeholder="$t('Field of study')"
                            />
                            <InputError :message="form.errors.field_of_study" class="mt-2" />
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
                            <Label for="grade" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Grade')}}
                            </Label>
                            <Input
                                id="grade"
                                type="text"
                                v-model="form.grade"
                                class="w-full"
                                :placeholder="$t('Grade')"
                            />
                            <InputError :message="form.errors.grade" class="mt-2" />
                        </div>

                        <div class="grid w-full  items-center gap-1.5 md:col-span-2">
                            <Label for="activities" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Activities')}}
                            </Label>
                            <Textarea
                                id="activities"
                                type="text"
                                v-model="form.activities"
                                rows="4"
                                class="w-full"
                                :placeholder="$t('Activities')"
                            />
                            <InputError :message="form.errors.activities" class="mt-2" />
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

                <Button variant="destructive" @click="crudDeleteManagerRef.open(props.education)">
                    {{ $t('Delete') }}
                </Button>

                <DialogClose as-child>
                    <Button variant="secondary">
                        {{ $t('Cancel') }}
                    </Button>
                </DialogClose>
                <Button @click="submit">
                    {{ $t('Save') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>

    <Delete
        v-if="crudDeleteManagerRef.isModalOpen"
        :openModal="crudDeleteManagerRef.open"
        :close="crudDeleteManagerRef.close"
        :education="crudDeleteManagerRef.model"
    />
</template>
