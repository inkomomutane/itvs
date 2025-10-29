<script setup lang="ts">
import type { DateRange, DateValue } from 'reka-ui';
import { CalendarDate, DateFormatter,parseDate, getLocalTimeZone, today } from '@internationalized/date';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { ref, watch,nextTick,computed } from 'vue';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { RangeCalendar } from '@/components/ui/range-calendar';
import { Input } from '@/components/ui/input';
import { t } from '@/lib/utils';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { parseToCalendarDate } from './RangePickerUtils';
import { useDebounceFn} from '@vueuse/core';
import { usePage } from '@inertiajs/vue3';

const df = new DateFormatter('en-GB', {
    dateStyle: 'medium',
});
const getInputTypedStringDate = (input: string): CalendarDate | null => {
    if (!input || input.trim() === '') {
        return null;
    }
    return parseToCalendarDate(input);
};

const value = defineModel<DateRange>({
    type: Object as () => DateRange,
    default: () => ({
        start: null,
        end: null,
    })
});


const startDateRef = ref();
const quickOptions = [
    {
        label: t('Today'),
        range: () => {
            const now = today(getLocalTimeZone());
            return { start: now, end: now };
        },
    },
    {
        label: t('This month'),
        range: () => {
            const now = today(getLocalTimeZone());
            const startOfMonth = now.set({ day: 1 });
            return { start: startOfMonth, end: now };
        },
    },
    {
        label: t('Last 30 days'),
        range: () => {
            const now = today(getLocalTimeZone());
            const startOfLast30Days = now.subtract({ days: 29 });
            return { start: startOfLast30Days, end: now };
        },
    },
    {
        label: t('This quarter'),
        range: () => {
            const now = today(getLocalTimeZone());
            const currentMonth = now.month;
            const startMonth = currentMonth - (currentMonth % 3);
            const startOfQuarter = now.set({ month: startMonth, day: 1 });
            return { start: startOfQuarter, end: now };
        },
    },
    {
        label: t('This year'),
        range: () => {
            const now = today(getLocalTimeZone());
            const startOfYear = now.set({ month: 1, day: 1 });
            return { start: startOfYear, end: now };
        },
    },
    {
        label: t('Last month'),
        range: () => {
            const now = today(getLocalTimeZone());
            const lastMonth = now.subtract({ months: 1 });
            const startOfLastMonth = lastMonth.set({ day: 1 });
            const endOfLastMonth = startOfLastMonth.add({ months: 1 }).subtract({ days: 1 });
            return { start: startOfLastMonth, end: endOfLastMonth };
        },
    },
    {
        label: t('Last quarter'),
        range: () => {
            const now = today(getLocalTimeZone());
            const currentMonth = now.month;
            const currentQuarter = Math.floor(currentMonth / 3);

            let startOfLastQuarter;
            if (currentQuarter === 0) {
                startOfLastQuarter = now.subtract({ years: 1 }).set({ month: 10, day: 1 });
            } else {
                startOfLastQuarter = now.set({ month: currentQuarter * 3 - 2, day: 1 });
            }

            const endOfLastQuarter = startOfLastQuarter.add({ months: 3 }).subtract({ days: 1 });
            return { start: startOfLastQuarter, end: endOfLastQuarter };
        },
    },
    {
        label: t('Last year'),
        range: () => {
            const now = today(getLocalTimeZone());
            const lastYear = now.subtract({ years: 1 });
            const startOfLastYear = lastYear.set({ month: 1, day: 1 });
            const endOfLastYear = startOfLastYear.add({ years: 1 }).subtract({ days: 1 });
            return { start: startOfLastYear, end: endOfLastYear };
        },
    },
    {
        label: t('Custom'),

        range: () => {
            return { start: value.value.start, end: value.value.end };
        },
        action: () => {
            nextTick(() => {
                if (startDateRef.value) {
                    startDateRef.value.focus();
                }
            });
        },
    },
].map(option => ({
    ...option,
    action: option.action || (() => {
        value.value = option.range();
    }),
    range: option.range,
}));

const  isSameDate = (a, b) =>  {
    return a && b && a.compare(b) === 0;
}

const  isOptionActive = (option :any) =>  {
    if (!option.range) return false;
    return (
        isSameDate(value.value.start, option.range().start) &&
        isSameDate(value.value.end, option.range().end)
    );
}

const activeOptionLabel = computed(() => {
    const found = quickOptions.find(opt => isOptionActive(opt));
    return found ? found.label : t('Custom');
});

const displayStartDateText = ref('');
const displayEndDateText = ref('');
const getFormattedValidDate = (date: CalendarDate | DateValue | null | undefined): string => {
    if (!date) {
        return '';
    }
    if (!(typeof date === 'object' && date instanceof CalendarDate)) {
        return '';
    }
    return df.format(date.toDate(getLocalTimeZone()));
};

// Supported formats
watch(
    () => value.value,
    (newValue) => {
        // Reset display texts if no value or both dates are missing
        if (!newValue || (!newValue.start && !newValue.end)) {
            displayStartDateText.value = '';
            displayEndDateText.value = '';
            return;
        }

        // Swap if start is after end
        if (newValue.start && newValue.end && newValue.start.compare(newValue.end) > 0) {
            value.value = { start: newValue.end, end: newValue.start };
            return;
        }

        // Set display texts
        displayStartDateText.value = getFormattedValidDate(newValue.start);
        displayEndDateText.value = getFormattedValidDate(newValue.end);

    },
    { immediate: true, deep: true },
);
const changeStartDateFromInput = useDebounceFn((input: string | number) => {
    const prev = value.value.start;
    const parsedDate = getInputTypedStringDate(input.toString());
    if (parsedDate) {
        value.value = {
            ...value.value,
            start: parsedDate,
        };
    } else {
        value.value = {
            ...value.value,
            start: prev,
        };
    }
},50);
const changeEndDateFromInput = useDebounceFn((input: string | number) => {
    const parsedDate = getInputTypedStringDate(input.toString());

    const prev = value.value.end;
    if (parsedDate) {
        value.value = {
            ...value.value,
            end: parsedDate,
        };
    } else {
        value.value = {
            ...value.value,
            end: prev,
        };
    }
},50);
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" :class="cn('w-[280px] justify-start text-left font-normal', !value && 'text-muted-foreground')">
                <CalendarIcon class="mr-2 h-4 w-4" />
                <template v-if="value.start">
                    <template v-if="value.end">
                        {{ df.format(value.start.toDate(getLocalTimeZone())) }} - {{ df.format(value.end.toDate(getLocalTimeZone())) }}
                    </template>

                    <template v-else>
                        {{ df.format(value.start.toDate(getLocalTimeZone())) }}
                    </template>
                </template>
                <template v-else> Pick a date </template>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
            <div class="flex w-full md:hidden md:items-center md:justify-between">
                <Select :class="cn('w-full border-none')">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select" :class="cn('w-full rounded-none border-0 shadow-none')" />
                    </SelectTrigger>
                    <SelectContent class="w-full">
                        <SelectItem v-for="item in quickOptions" :key="item.label" :value="item.label.toString()" @select="item.action">
                            {{ item.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div class="flex h-full flex-col md:flex-row">
                <div class="">
                    <div class="hidden flex-col border-r p-4 md:flex">

                        <template v-for="option in quickOptions" :key="option.label" >
                            <Button
                                variant="ghost"
                                @click="option.action"
                                :class="cn('w-full justify-start text-left font-normal hover:bg-accent hover:text-accent-foreground border-l-4 border-transparent ps-2',{
                                     ' border-primary rounded-l-none  font-bold' :activeOptionLabel === option.label,
                                })"
                            >
                                {{ option.label }}
                            </Button>
                        </template>

                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex-grow">
                        <RangeCalendar v-model="value" initial-focus :number-of-months="2" @update:start-value="(startDate) => (value.start = startDate)" />
                    </div>
                    <div class="flex h-fit flex-col items-center justify-between gap-4 border-t p-4 sm:flex-row">
                        <div>
                            <Input ref="startDateRef" :model-value="displayStartDateText" @update:model-value="changeStartDateFromInput" />
                        </div>
                        <div>
                            <Input :model-value="displayEndDateText" @update:model-value="changeEndDateFromInput" />
                        </div>
                    </div>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
