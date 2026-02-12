<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardAction, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { TrendingDown, TrendingUp } from 'lucide-vue-next';
import axios from 'axios';
import { NUMBER } from '@/lib/utils';
import { onMounted, ref } from 'vue';
import CardChartSkeleton from '@/pages/Dashboard/CardChartSkeleton.vue';

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    footer_title: {
        type: String,
        default: ''
    },
    route: { type: String, default: '' }
});
const loadingData = ref(true);
const asyncValue = ref(0);
const percentageChange = ref(0);


const fetchData = async () => {
      if(props.route) {
          axios.get(props.route)
              .then((response) => {
                  const data = response.data;
                  console.log(data);
                  asyncValue.value = NUMBER(data.value);
                  percentageChange.value = parseFloat(data.change_percentage);
                  loadingData.value = false;
              })
              .catch((error) => {
                  console.error('Error fetching overdue payables data:', error);
                  loadingData.value = false;
                  asyncValue.value = 0;
              });
      }
};

onMounted(() => {
    fetchData();
});


</script>

<template>
    <CardChartSkeleton v-if="loadingData" />
    <Card v-else class="border-none shadow-none h-fit">
        <CardHeader>
            <CardDescription>
                {{ title }}
            </CardDescription>
            <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                 {{ NUMBER(asyncValue ?? 0,0).format() }}
            </CardTitle>
            <CardAction>
                <Badge variant="outline">

                    <TrendingUp v-if="percentageChange && percentageChange > 0" />
                    <TrendingDown v-else />

                     {{  percentageChange > 0 ? '+' : '' }}{{ NUMBER(percentageChange,2).format() }}%
                </Badge>
            </CardAction>
        </CardHeader>
        <CardFooter class="flex-col items-start gap-1.5 text-sm">
            <div class="line-clamp-1 flex gap-2 mt-2 flex gap-x-4 text-xs text-muted-foreground">
                {{ footer_title }}
                <TrendingUp  class="size-4" v-if="percentageChange && percentageChange > 0" />
                <TrendingDown class="size-4"  v-else />
            </div>
        </CardFooter>
    </Card>
</template>
