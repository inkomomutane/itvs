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
import { DisciplinaryRecordDto, UserDto } from '@/types/generated';
import { useForm } from '@inertiajs/vue3'
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea'
import { PropType, watch } from 'vue';

const props = defineProps({
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
    member: {
        type: Object as PropType<UserDto>,
        required: true,
    },
    disciplinaryRecord: {
        type: Object as PropType<DisciplinaryRecordDto>,
        required: true,
    },
});

const form = useForm<DisciplinaryRecordDto>({
    infraction: '',
    penalty: '',
    organ: '',
    process_number: '',
    record_date: null,
});

watch(() => props.disciplinaryRecord, (newRecord) => {
    if (newRecord) {
        form.infraction = newRecord.infraction || '';
        form.penalty = newRecord.penalty || '';
        form.organ = newRecord.organ || '';
        form.process_number = newRecord.process_number || '';
        form.record_date = newRecord.record_date ? new Date(newRecord.record_date) : null;
    }
}, { immediate: true });

const submit =  () => {
    form.put(route('member.disciplinary-record.update',{
        member: props.member?.id,
        disciplinaryRecord: props.disciplinaryRecord?.id,
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
        <DialogScrollContent class="max-w-2xl" >
            <DialogHeader>
                <DialogTitle> {{$t('Edit disciplinary record')}}</DialogTitle>
            </DialogHeader>
            <div>
                <form @submit.prevent="submit">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="infraction" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Infraction')}}
                            </Label>
                            <Input
                                id="infraction"
                                type="text"
                                v-model="form.infraction"
                                class="w-full"
                                :placeholder="$t('Infraction')"
                            />
                            <InputError :message="form.errors.infraction" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="penalty" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Penalty')}}
                            </Label>
                            <Input
                                id="penalty"
                                type="text"
                                v-model="form.penalty"
                                class="w-full"
                                :placeholder="$t('Penalty')"
                            />
                            <InputError :message="form.errors.penalty" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="organ" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Organ')}}
                            </Label>
                            <Input
                                id="organ"
                                type="text"
                                v-model="form.organ"
                                class="w-full"
                                :placeholder="$t('Organ')"
                            />
                            <InputError :message="form.errors.organ" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="process_number" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Process number')}}
                            </Label>
                            <Input
                                id="process_number"
                                type="text"
                                v-model="form.process_number"
                                class="w-full"
                                :placeholder="$t('Process number')"
                            />
                            <InputError :message="form.errors.process_number" class="mt-2" />
                        </div>

                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="record_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{$t('Record date')}}
                            </Label>
                            <VueDatePicker :placeholder="$t('Record date')" format="dd/MM/yyyy" auto-apply v-model="form.record_date"/>
                            <InputError :message="form.errors.record_date" class="mt-2" />
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
