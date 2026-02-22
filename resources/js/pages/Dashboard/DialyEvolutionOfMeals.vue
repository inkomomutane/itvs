<script setup lang="ts">
import type {
    ChartConfig,
} from "@/components/ui/chart"

import { VisAxis, VisLine, VisXYContainer } from "@unovis/vue"
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import {
    ChartContainer,
    ChartCrosshair,
    ChartTooltip,
    ChartTooltipContent,
    componentToString,
} from "@/components/ui/chart"
import { computed, onMounted, ref } from 'vue';
import { format } from "date-fns"
import axios from 'axios';
import { NUMBER } from '@/lib/utils';

const description = "Evolução diária das reservas de refeições por plataforma"

const chartData = ref([]);

type Data = typeof chartData[number]

const chartConfig = {
    reserved: {
        label: "Reservas",
        color: "var(--chart-5)",
    },
    eaten: {
        label: "Consumidos",
        color: "var(--chart-2)",
    },
} satisfies ChartConfig

const activeChart = ref("reserved")
const total = computed(() => ({
    reserved: chartData.value.reduce((acc, curr) => acc + curr.reserved, 0),
    eaten: chartData.value.reduce((acc, curr) => acc + curr.eaten, 0),
}))


const loadingData = ref(true);


const fetchData = async () => {
    axios.get(route('get-last-6-months-meals-trend'))
        .then((response) => {
            const data = response.data as any[];
            console.log(data);
            chartData.value = data.map((item: any) => ({
                date: new Date(item.date),
                reserved:  parseFloat(item.reserved),
                eaten:  parseFloat(item.eaten),
            }));

            loadingData.value = false;
        })
        .catch((error) => {
            console.error('Error fetching overdue payables data:', error);
            loadingData.value = false;
        });
};

onMounted(() => {
    fetchData();
});
</script>

<template>
    <Card class="py-4 sm:py-0 shadow-none">
        <CardHeader class="flex flex-col items-stretch border-b !p-0 sm:flex-row">
            <div class="flex flex-1 flex-col justify-center gap-1 px-6 pb-3 sm:pb-0">
                <CardTitle>Evolução Diária</CardTitle>
                <CardDescription>
                    Evolução diária das reservas de refeições por plataforma
                </CardDescription>
            </div>
            <div class="flex">
                <button
                    v-for="chart in ['reserved', 'eaten'] as (keyof typeof chartConfig)[]"
                    :key="chart"
                    :data-active="activeChart === chart"
                    class="data-[active=true]:bg-muted/50 flex flex-1 flex-col justify-center gap-1 border-t px-6 py-4 text-left even:border-l sm:border-t-0 sm:border-l sm:px-8 sm:py-6"
                    @click="activeChart = chart"
                >
          <span class="text-muted-foreground text-xs">
            {{ chartConfig[chart].label }}
          </span>
                    <span class="text-lg leading-none font-bold sm:text-3xl">
            {{ total[chart as keyof typeof total].toLocaleString() }}
          </span>
                </button>
            </div>
        </CardHeader>
        <CardContent class="px-2 sm:p-6">
            <ChartContainer :config="chartConfig" class="aspect-auto h-[250px] w-full" cursor>
                <VisXYContainer
                    :data="chartData"
                    :margin="{ left: -24 }"
                    :y-domain="[0, undefined]"
                >

                    <VisLine
                        :x="(d: Data) => d.date"
                        :y="[(d: Data) => d.eaten, (d: Data) =>d.reserved]"
                        :color="(d: Data, i: number) => [chartConfig.eaten.color, chartConfig.reserved.color][i]"
                        :line-width="2"
                    />
                    <VisAxis
                        type="x"
                        :x="(d: Data) => d.date"
                        :tick-line="false"
                        :domain-line="false"
                        :grid-line="false"
                        :tick-format="(d: number) => {
                          const date = new Date(d)
                          return date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                          })
                        }"
                    />
                    <VisAxis
                        type="y"
                        :num-ticks="3"
                        :tick-line="false"
                        :domain-line="false"
                    />
                    <ChartTooltip />
                    <ChartCrosshair
                        :template="componentToString(chartConfig, ChartTooltipContent, {
                          labelFormatter(d) {
                            return new Date(d).toLocaleDateString('en-US', {
                              month: 'short',
                              day: 'numeric',
                              year: 'numeric',
                            })
                          },
                        })"
                        :color="chartConfig.reserved.color"
                    />
                </VisXYContainer>
            </ChartContainer>
        </CardContent>
    </Card>
</template>
