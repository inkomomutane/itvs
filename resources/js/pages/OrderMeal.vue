<script setup lang="ts">
import { MEAL_BG_COLORS, MEAL_COLORS, MEAL_ICONS, MealType } from '@/types';
import {
    Dialog,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import {
    Field,
    FieldContent,
    FieldDescription, FieldError,
    FieldGroup,
    FieldLabel,
    FieldSet,
    FieldTitle
} from '@/components/ui/field';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import type { DateValue } from '@internationalized/date';
import { DateFormatter, getLocalTimeZone, today } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger
} from '@/components/ui/popover';
import { ref, type Ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Skeleton } from '@/components/ui/skeleton';

const props = defineProps({
    close: {
        type: Function,
        required: true
    },
    openModal: {
        type: Boolean,
        required: true
    },
    mealType: {
        type: String as () => MealType,
        required: true
    }
});

const meals = ref([])
const fetchingMeals = ref(false);
const fetchAvailableMeals = async (
    date : DateValue,
    mealType: MealType
) => {
    try {
        fetchingMeals.value = true;
        const response = await axios.get(route( 'meals.available',{
            date: date.toString(),
            period: mealType
        }));
        meals.value = response.data;
        fetchingMeals.value = false;
    } catch (error) {
        console.error('Error fetching available meals:', error);
    }
};



const defaultPlaceholder = today(getLocalTimeZone());
const date = ref() as Ref<DateValue>;

const df = new DateFormatter('en-US', {
    dateStyle: 'long'
});

const form = useForm({
    sap_number: '',
    period: props.mealType,
    meal_id: null,
    date: null
});


const submit = () => {
    form.transform((data) => {
        return {
            ...data,
            date: data.date ? data.date.toString() : null
        };
    })  .post(route('meals.reserve'), {
        onSuccess: () => {
            props.close();
            form.reset();
        }
    });
};
watch(() => form.date, (newDate) => {
    if (newDate && props.mealType) {
        fetchAvailableMeals(newDate, props.mealType);
    }
});




</script>

<template>
    <Dialog @update:open="props.close" :open="props.openModal">
        <DialogScrollContent class="max-w-4xl ">
            <DialogHeader>
                <DialogTitle> {{ 'MENU' }}</DialogTitle>
            </DialogHeader>
            <DialogDescription class="space-y-4 space-x-4 grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <FieldGroup>
                    <Field>
                        <FieldLabel for="data-reserva">
                            Data da Reserva
                        </FieldLabel>
                        <FieldContent>
                            <Popover v-slot="{ close }">
                                <PopoverTrigger as-child>
                                    <Button
                                        variant="outline"
                                        :class="cn(' justify-start text-left font-normal', !form.date && 'text-muted-foreground')"
                                    >
                                        <CalendarIcon />
                                        {{ form.date ? df.format(form.date.toDate(getLocalTimeZone())) : 'Selecione a data' }}
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-auto p-0" align="start">
                                    <Calendar
                                        v-model="form.date"
                                        :min-value="today(getLocalTimeZone())"
                                        :default-placeholder="defaultPlaceholder"
                                        layout="month-and-year"
                                        initial-focus
                                        @update:model-value="close"
                                    />
                                </PopoverContent>
                            </Popover>
                        </FieldContent>
                         <FieldError v-if="form.errors.date">
                            {{ form.errors.date }}
                         </FieldError>
                    </Field>
                </FieldGroup>
                <FieldGroup>
                    <Field>
                        <FieldContent class="flex flex-col gap-1">
                            <FieldTitle>
                                Número de SAP
                            </FieldTitle>
                            <Input
                                id="sap_number"
                                v-model="form.sap_number"
                                type="text"
                                placeholder="Número de SAP"
                                class="w-full px-3 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </FieldContent>
                        <FieldError v-if="form.errors.sap_number">
                                {{ form.errors.sap_number }}
                            </FieldError>
                    </Field>
                </FieldGroup>
                <FieldGroup class="md:col-span-2">
                    <FieldSet class="grid grid-cols-1 md:grid-cols-2">
                        <RadioGroup v-if="!fetchingMeals" v-for="meal in meals" :key="meal.id" v-model="form.meal_id" class="cursor-pointer">
                            <FieldLabel :for="meal.id"
                                        :class="cn('ring ring-transparent cursor-pointer',{
                                             'has-data-[state=checked]:border-rose-400 has-data-[state=checked]:ring-rose-400 has-data-[state=checked]:bg-rose-400/5'  : props.mealType === 'breakfast',
                                                'has-data-[state=checked]:border-green-400 has-data-[state=checked]:ring-green-400 has-data-[state=checked]:bg-green-400/5': props.mealType === 'lunch',
                                                'has-data-[state=checked]:border-slate-400 has-data-[state=checked]:ring-slate-400 has-data-[state=checked]:bg-slate-400/5': props.mealType === 'dinner',
                                                'has-data-[state=checked]:border-amber-400 has-data-[state=checked]:ring-amber-400 has-data-[state=checked]:bg-amber-400/5': props.mealType === 'snack',
                                       })"
                            >
                                <Field orientation="horizontal"


                                >
                                    <FieldContent :class="cn('flex flex-cols md:flex-row items-center gap-4')">
                                        <div>
                                            <div
                                                :class="cn('p-4 rounded-xl', MEAL_BG_COLORS[props.mealType], MEAL_COLORS[props.mealType])">
                                                <component :is="MEAL_ICONS[props.mealType]"
                                                           class="w-10 h-10 md:w-12 md:h-12" />
                                            </div>
                                        </div>
                                        <div>
                                            <FieldTitle
                                                :class="cn('text-lg md:text-xl font-bold text-foreground mb-1')">
                                                {{ meal.name }}
                                            </FieldTitle>
                                            <FieldDescription>
                                                {{ meal.description }}
                                            </FieldDescription>
                                        </div>
                                    </FieldContent>
                                    <RadioGroupItem :id="meal.id" :value="meal.id" :class="cn({
                                            'data-[state=checked]:border-rose-400 data-[state=checked]:ring-rose-400 data-[state=checked]:bg-rose-400/5'  : props.mealType === 'breakfast',
                                            'data-[state=checked]:border-green-400 data-[state=checked]:ring-green-400 data-[state=checked]:bg-green-400/5': props.mealType === 'lunch',
                                            'data-[state=checked]:border-slate-400 data-[state=checked]:ring-slate-400 data-[state=checked]:bg-slate-400/5': props.mealType === 'dinner',
                                            'data-[state=checked]:border-amber-400 data-[state=checked]:ring-amber-400 data-[state=checked]:bg-amber-400/5': props.mealType === 'snack',
                                    })" />
                                </Field>
                            </FieldLabel>
                        </RadioGroup>
                        <Skeleton v-else class="h-24 md:h-32 w-full col-span-2 rounded-lg">
                            <div class="h-full w-full animate-pulse bg-gray-200 rounded-lg"></div>
                        </Skeleton>
                         <FieldError v-if="form.errors.meal_id" class="col-span-2">
                            {{ form.errors.meal_id }}
                         </FieldError>
                    </FieldSet>
                </FieldGroup>
            </DialogDescription>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="secondary">
                        {{ $t('Cancelar') }}
                    </Button>
                </DialogClose>
                <Button @click="submit" :disabled="form.processing">
                    {{ $t('Reservar') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
