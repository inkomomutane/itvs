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
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import type { DateValue} from '@internationalized/date';
import { parseAbsolute } from '@internationalized/date';
import {
    DateFormatter,
    getLocalTimeZone,
    today,
    parseDate,
    CalendarDate
} from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { PropType, ref } from 'vue';
import { cn,t } from '@/lib/utils';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { TiptapContent, TiptapProvider, TiptapStatusBar, TiptapToolbar } from '@/components/ui/tiptap';
import StarterKit from '@tiptap/starter-kit';
import { useEditor } from '@tiptap/vue-3';
import { KeyValueDto, RecipeDto } from '@/types/generated';
import MSelect from '@/components/MSelect.vue';
import { Textarea } from '@/components/ui/textarea';


const props = defineProps({
    close: {
        type: Function,
        required: true
    },
    openModal: {
        type: Boolean,
        required: true
    },
    date: {
        type: String,
        default: []
    },
    recipe : {
        type: Object as PropType<RecipeDto>,
        required: true
    },
    periods : {
        type: Array<KeyValueDto>,
        default: () => []
    }
});

const df = new DateFormatter('en-US', {
    dateStyle: 'long'
});

const items = [
    { value: 0, label: t('Today') },
    { value: 1, label: t('Tomorrow') },
    { value: 2, label: t('In 2 days') },
    { value: 3, label: t('In 3 days') },
    { value: 4, label: t('In 4 days') },
    { value: 5, label: t('In 5 days') },
    { value: 6, label: t('In 6 days') },
];

const value = ref<DateValue>(props.recipe.date ? parseAbsolute(props.recipe.date) : undefined);
const editor = useEditor({
    content: props.recipe?.description || '',
    extensions: [
        StarterKit
    ]
});
const name = ref('');


const form = useForm<RecipeDto>({
    name: props.recipe.name || '',
    description: null,
    date: props.recipe.date || '',
    period: props.recipe.period || null,
});

const submit = () => {

    // form.description = editor.value || '';
    form.date =  new CalendarDate(
        (value.value as CalendarDate).year,
        (value.value as CalendarDate).month,
        (value.value as CalendarDate).day
    ).toString();

    form.post(route('update-recipe',{
        recipe: props.recipe.id
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
        <DialogScrollContent class="max-w-5xl">
            <DialogHeader>
                <DialogTitle> {{ $t('Edit recipe') }}</DialogTitle>
            </DialogHeader>
            <div>
                <div >
                    <div class="grid  gap-4">
                        <div class="grid w-full  items-center gap-1.5">
                            <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $t('Name') }}
                            </Label>
                            <Input
                                id="name"
                                type="text"
                                v-model="form.name"
                                class="w-full"
                                :placeholder="$t('Recipe name')"
                            />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                        <div class="grid w-full  items-center gap-1.5">
                            <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $t('Period') }}
                            </Label>
                            <MSelect :options="props.periods"
                                     v-model="form.period"
                                     :reduce="(e) => e.key"
                                     :get-label="(e) => t(e.value)"
                                     :placeholder="$t('Period')"
                            />
                            <InputError :message="form.errors.period" class="mt-2" />
                        </div>
                        <div class="grid w-full  items-center gap-1.5">
                            <Label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $t('Description') }}
                            </Label>
                            <Textarea rows="8" v-model="form.description" :placeholder="$t('Recipe description')" />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>
                        <div class="grid w-full  items-center gap-1.5">
                            <Label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $t('Date') }}
                            </Label>
                            <Popover>
                                <PopoverTrigger as-child>
                                    <Button
                                        variant="outline"
                                        :class="cn(  'justify-start text-left font-normal',!value && 'text-muted-foreground')"
                                    >
                                        <CalendarIcon class="mr-2 h-4 w-4" />
                                        {{ value ? df.format(value.toDate(getLocalTimeZone())) : 'Pick a date' }}
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="flex min-w-32 w-full flex-col gap-y-2 p-2">
                                    <Select
                                        @update:model-value="(v) => {
                                                  if (!v) return;
                                                  value = today(getLocalTimeZone()).add({ days: Number(v) });
                                                }"
                                    >
                                        <SelectTrigger class="w-full min-w-32 justify-between">
                                            <SelectValue placeholder="Select" class="w-full min-w-32 justify-between" />
                                        </SelectTrigger>
                                        <SelectContent class="!w-full">
                                            <SelectItem v-for="item in items" :key="item.value"
                                                        :value="item.value.toString()">
                                                {{ item.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <Calendar :min-value="today(getLocalTimeZone())" v-model="value" />
                                </PopoverContent>
                            </Popover>
                            <InputError :message="form.errors.date" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{ $t('Cancel') }}
                    </Button>
                </DialogClose>
                <Button @click="submit" type="button">
                    {{ $t('Update') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
