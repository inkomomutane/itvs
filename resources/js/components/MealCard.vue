<script setup lang="ts">
import { MEAL_BG_COLORS, MEAL_COLORS, MEAL_ICONS, MealType } from '@/types';
import { MEAL_LABELS, MEAL_TIMES } from '@/types';
import { Coffee, Utensils, Moon, Cookie } from 'lucide-vue-next';
import { cn } from '@/lib/utils';

const props = defineProps<{
  mealType: MealType;
}>();


</script>

<template>
  <div
    :class="cn('meal-card w-full text-left', 'animate-fade-in','cursor-pointer rounded-md border-2  p-6 hover:shadow-md transition-shadow duration-200', {
        'hover:border-rose-400': props.mealType === 'breakfast',
        'hover:border-green-400': props.mealType === 'lunch',
        'hover:border-slate-400': props.mealType === 'dinner',
        'hover:border-amber-400': props.mealType === 'snack',
    })"
  >
    <div class="flex items-start gap-4">
      <div :class="cn('p-4 rounded-xl', MEAL_BG_COLORS[props.mealType], MEAL_COLORS[props.mealType])">
        <component :is="MEAL_ICONS[props.mealType]" class="w-10 h-10 md:w-12 md:h-12" />
      </div>

      <div class="flex-1">
        <h3 class="text-xl md:text-2xl font-bold text-foreground mb-1">
          {{ MEAL_LABELS[props.mealType] }}
        </h3>
        <p class="text-muted-foreground font-medium">
          {{ MEAL_TIMES[props.mealType] }}
        </p>
      </div>

      <div :class="cn('px-3 py-1 rounded-full text-sm font-medium', MEAL_BG_COLORS[props.mealType], MEAL_COLORS[props.mealType])">
        Menu
      </div>
    </div>
  </div>
</template>
