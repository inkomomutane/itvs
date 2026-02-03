<script setup lang="ts">
import { MEAL_BG_COLORS, MEAL_COLORS, MEAL_ICONS, MEAL_LABELS, MealType } from '@/types';
import { cn } from '@/lib/utils';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { useForm } from '@inertiajs/vue3';
import {parseDate} from '@internationalized/date';
import {
    Dialog,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader, DialogScrollContent,
    DialogTitle
} from '@/components/ui/dialog';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldSet,
    FieldTitle
} from '@/components/ui/field';
import { Skeleton } from '@/components/ui/skeleton';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { ref, watch } from 'vue';
import axios from 'axios';
import { formatDate } from '@/helpers';
import { Badge } from '@/components/ui/badge';

const props = defineProps({
    close: {
        type: Function,
        required: true
    },
    openModal: {
        type: Boolean,
        required: true
    },
});

const form = useForm({
    sap_number: '',
    meal_id: null as string | null,
});


const meals = ref([])
const fetchingMeals = ref(true);
const fetchReservedMeals = async (sapNumber: string) => {
     try {
            fetchingMeals.value = true;
            const response = await axios.get(route('meals.reserved',{
                sap_number: sapNumber
            }));
            meals.value = response.data;

            console.log(meals.value);
            fetchingMeals.value = false;
     }catch (e) {
         fetchingMeals.value = false;
         meals.value = [];
     }
};


const submit = () => {
    form.post(route('meals.confirmation',{
        meal_id: form.meal_id,
        sap_number: form.sap_number,
    }),{
        onSuccess: () => {
            form.reset();
            meals.value = [];
            props.close();
        }
    });
};




watch(() => form.sap_number, async (newSapNumber) => {
    if (newSapNumber.length > 0) {
        await fetchReservedMeals(newSapNumber);
    } else {
        meals.value = [];
    }
});
</script>

<template>
    <Dialog @update:open="props.close" :open="props.openModal">
        <DialogScrollContent class="max-w-4xl ">
            <DialogHeader>
                <DialogTitle> {{ 'CONFIRM' }}</DialogTitle>
            </DialogHeader>
            <DialogDescription class="space-y-4 space-x-4 grid grid-cols-1 gap-x-6">
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
                    <FieldSet class="grid grid-cols-1">
                        <RadioGroup v-if="!fetchingMeals" v-for="meal in meals" :key="meal.id" v-model="form.meal_id" class="cursor-pointer">
                            <FieldLabel :for="meal.id"
                                        :class="cn('ring ring-transparent cursor-pointer',{
                                             'has-data-[state=checked]:border-rose-400 has-data-[state=checked]:ring-rose-400 has-data-[state=checked]:bg-rose-400/5'  : meal.recipe.period === 'breakfast',
                                                'has-data-[state=checked]:border-green-400 has-data-[state=checked]:ring-green-400 has-data-[state=checked]:bg-green-400/5': meal.recipe.period === 'lunch',
                                                'has-data-[state=checked]:border-slate-400 has-data-[state=checked]:ring-slate-400 has-data-[state=checked]:bg-slate-400/5': meal.recipe.period === 'dinner',
                                                'has-data-[state=checked]:border-amber-400 has-data-[state=checked]:ring-amber-400 has-data-[state=checked]:bg-amber-400/5': meal.recipe.period === 'snack',
                                       })"
                            >
                                <Field orientation="horizontal" >
                                    <FieldContent :class="cn('flex flex-cols md:flex-row items-center gap-4')">
                                        <div>
                                            <div
                                                :class="cn('p-4 rounded-xl', MEAL_BG_COLORS[meal.recipe.period], MEAL_COLORS[meal.recipe.period])">
                                                <component :is="MEAL_ICONS[meal.recipe.period]"
                                                           class="w-10 h-10 md:w-12 md:h-12" />
                                            </div>
                                        </div>
                                        <div>
                                            <FieldTitle
                                                :class="cn('text-lg md:text-xl font-bold text-foreground mb-1')">
                                                {{ meal.recipe.name }}
                                            </FieldTitle>

                                            <div
                                                class="capitalize text-xs md:text-sm text-muted-foreground mb-2">
                                                 {{ formatDate(parseDate(meal.meal_date)) }}
                                            </div>

                                            <Badge variant="outline">
                                                {{ MEAL_LABELS[meal.recipe.period] }}
                                            </Badge>
                                            <FieldDescription>
                                                {{ meal.recipe.description }}
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
                        <Skeleton v-else-if="(fetchingMeals == true) || ( meals.length <= 0) " class="h-24 md:h-32 w-full col-span-2 rounded-lg">
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
                    {{ $t('CONFIRMAR') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
