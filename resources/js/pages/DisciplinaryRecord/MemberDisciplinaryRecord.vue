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
import { DisciplinaryRecordDto } from '@/types/generated';
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
    disciplinaryRecord: {
        type: Object as PropType<DisciplinaryRecordDto>,
        required: true
    },
});

</script>

<template>
    <Dialog @update:open="props.close" :open="props.openModal">
        <DialogScrollContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>{{ $t('Disciplinary Record Details') }}</DialogTitle>
            </DialogHeader>
            <div class="py-4">
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
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ disciplinaryRecord.record_date || 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{ $t('Close') }}
                    </Button>
                </DialogClose>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
