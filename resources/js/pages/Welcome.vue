<!-- resources/js/pages/FrontPage.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import type { MealType } from '@/types';
import { Head,usePage } from  '@inertiajs/vue3';
import MealCard from '@/components/MealCard.vue';
import 'vue-sonner/style.css'
// import MenuSelection from '@/components/MenuSelection';
// import ConsumptionConfirmation from '@/components/ConsumptionConfirmation';
import { Coffee, Utensils, Moon, Cookie } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { CheckSquare } from 'lucide-vue-next';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldGroup,
    FieldLabel,
    FieldSet,
    FieldTitle,
} from '@/components/ui/field';

import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { cn, crudManager } from '@/lib/utils';
import OrderMeal from '@/pages/OrderMeal.vue';
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'vue-sonner'
const orderAMeal = ref(crudManager());
const confirmAMeal = ref(crudManager());
import { watch } from 'vue';
import ConfirmMeal from '@/pages/ConfirmMeal.vue';

const page  = usePage();
watch(() => page.props.messages, (value) => {
    if (value) {
        toast.info(value?.message ?? '',{
            description: '',
            position: 'top-center',
            timeout: 5000,
            type: 'info',
            class: 'max-w-md',
            action: {
                label: 'Close',
                onClick: () => {
                    toast.dismiss();
                },
            }
        });
    }
});
</script>

<template>
    <Toaster />
    <Head title="Bem-vindo" />
    <div class="min-h-screen bg-blue-300">
        <main class="p-4 md:p-8 flex justify-center items-center min-h-[calc(100vh-4rem)]">
            <div class="max-w-4xl mx-auto bg-white flex justify-center items-center p-6 md:p-12 rounded-lg shadow-lg w-full">
                <!-- If a meal is selected show MenuSelection -->
                <div v-if="selectedMeal" class="animate-fade-in">
                    <!--          <MenuSelection :mealType="selectedMeal" :onBack="backToMeals" />-->
                </div>

                <!-- Main selection UI -->
                <div v-else>
                    <!-- Title Section -->
                    <div class="text-center mb-8 animate-fade-in">
                        <h2 class="text-2xl md:text-3xl font-bold text-foreground mb-2 uppercase">
                            Menu do Dia
                        </h2>
                        <p class="text-muted-foreground text-lg">
                            Selecione a refeição para fazer a sua reserva
                        </p>
                    </div>

                    <!-- Meal Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
                        <MealCard mealType="breakfast" @click="orderAMeal.open('breakfast')" />
                        <MealCard mealType="lunch" @click="orderAMeal.open('lunch')" />
                        <MealCard mealType="dinner" @click="orderAMeal.open('dinner')" />
                        <MealCard mealType="snack" @click="orderAMeal.open('snack')" />
                    </div>

                    <!-- Consumption Confirmation Button -->
                    <div class="text-center animate-fade-in">
                        <Button
                            size="lg"
                            @click="confirmAMeal.open()"
                            class="btn-touch bg-green-400 hover:bg-success/90 text-success-foreground gap-2 w-full"
                        >
                            <CheckSquare class="w-5 h-5" />
                            Confirmar Consumo
                        </Button>
                    </div>
                </div>

            </div>
        </main>

        <OrderMeal
            v-if="orderAMeal.isModalOpen"
            :meal-type="orderAMeal.model"
            :open-modal="orderAMeal.isModalOpen"
            :close="orderAMeal.close"
        />

        <ConfirmMeal
            v-if="confirmAMeal.isModalOpen"
            :open-modal="confirmAMeal.isModalOpen"
            :close="confirmAMeal.close"
        />

    </div>
</template>
