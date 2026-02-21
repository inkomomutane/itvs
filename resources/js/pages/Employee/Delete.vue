<script setup lang="ts">

import {
    Dialog,
    DialogClose,
    DialogContent, DialogDescription,
    DialogFooter,
    DialogHeader, DialogScrollContent, DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { EducationDto, UserDto } from '@/types/generated';
import { useForm } from '@inertiajs/vue3'
import { PropType } from "vue"

const props = defineProps({
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
    employee: {
        type:  Object as PropType<UserDto>,
        default: []
    }
});

const form = useForm<UserDto>(props.education);

const submit =  () => {
    form.delete(route('delete-employee',{
        user : props.employee?.id,
    }), {
        preserveState: true,
        onSuccess: () => {
            props.close();
        }
    })
}
</script>
<template>
    <Dialog  @update:open="props.close"  :open="props.openModal" class="z-200">
        <DialogScrollContent class="max-w-2xl" >
            <DialogHeader>
                <DialogTitle> {{$t('Excluir')}}</DialogTitle>
            </DialogHeader>
            <DialogDescription>
                {{$t('Tens a certeza que deseja eliminar este funcionário? Esta ação não pode ser desfeita.')}}
            </DialogDescription>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{$t('Cancelar')}}
                    </Button>
                </DialogClose>
                <Button  @click="submit" variant="destructive">
                    {{$t('Excluir')}}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
