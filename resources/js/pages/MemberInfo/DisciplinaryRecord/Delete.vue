<script setup lang="ts">
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { DisciplinaryRecordDto, UserDto } from '@/types/generated';
import { router } from '@inertiajs/vue3';
import { PropType } from 'vue';

const props = defineProps({
    close: {
        type: Function,
        required: true
    },
    openModal: {
        type: Boolean,
        required: true
    },
    member: {
        type: Object as PropType<UserDto>,
        required: true,
    },
    disciplinaryRecord: {
        type: Object as PropType<DisciplinaryRecordDto>,
        required: true
    },
});

const deleteDisciplinaryRecord = () => {
    router.delete(route('member.disciplinary-record.delete', {
        member: props.member?.id,
        disciplinaryRecord: props.disciplinaryRecord?.id
    }), {
        preserveState: true,
        onSuccess: () => {
            props.close();
        }
    });
};
</script>

<template>
    <Dialog @update:open="props.close" :open="props.openModal">
        <DialogScrollContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>{{ $t('Delete Disciplinary Record') }}</DialogTitle>
            </DialogHeader>
            <div class="py-4">
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                    {{ $t('Are you sure you want to delete this disciplinary record?') }}
                </p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Infraction') }}
                        </Label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ disciplinaryRecord.infraction || 'N/A' }}</p>
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Penalty') }}
                        </Label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ disciplinaryRecord.penalty || 'N/A' }}</p>
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Organ') }}
                        </Label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ disciplinaryRecord.organ || 'N/A' }}</p>
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Process Number') }}
                        </Label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ disciplinaryRecord.process_number || 'N/A' }}</p>
                    </div>

                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $t('Record Date') }}
                        </Label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ disciplinaryRecord.record_date ? new Date(disciplinaryRecord.record_date).toLocaleDateString() : 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{ $t('Cancel') }}
                    </Button>
                </DialogClose>
                <Button variant="destructive" @click="deleteDisciplinaryRecord">
                    {{ $t('Delete') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
