<!-- resources/js/pages/FrontPage.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import type { MealType } from '@/types';
// import Header from '@/components/Header';
import MealCard from '@/components/MealCard.vue';
// import MenuSelection from '@/components/MenuSelection';
// import ConsumptionConfirmation from '@/components/ConsumptionConfirmation';
import { Button } from '@/components/ui/button';
import { CheckSquare } from 'lucide-vue-next';

const selectedMeal = ref<MealType | null>(null);
const showConsumption = ref(false);

function selectMeal(meal: MealType) {
  selectedMeal.value = meal;
}

function backToMeals() {
  selectedMeal.value = null;
}

function openConsumption() {
  showConsumption.value = true;
}

function closeConsumption() {
  showConsumption.value = false;
}
</script>

<template>
  <div class="min-h-screen bg-blue-400">
<!--    <Header />-->

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
            <MealCard mealType="breakfast" :onClick="() => selectMeal('breakfast')" />
            <MealCard mealType="lunch" :onClick="() => selectMeal('lunch')" />
            <MealCard mealType="dinner" :onClick="() => selectMeal('dinner')" />
            <MealCard mealType="snack" :onClick="() => selectMeal('snack')" />
          </div>

          <!-- Consumption Confirmation Button -->
          <div class="text-center animate-fade-in">
            <Button
              size="lg"
              @click="openConsumption"
              class="btn-touch bg-green-400 hover:bg-success/90 text-success-foreground gap-2 w-full"
            >
              <CheckSquare class="w-5 h-5" />
              Confirmar Consumo
            </Button>
          </div>
        </div>
      </div>
    </main>

<!--    <ConsumptionConfirmation-->
<!--      :open="showConsumption"-->
<!--      :onClose="closeConsumption"-->
<!--    />-->
  </div>
</template>
