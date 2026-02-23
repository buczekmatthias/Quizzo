<script setup lang="ts">
import type {
    CalendarDate,
    DateTimeDuration,
    DateValue,
} from '@internationalized/date';
import {
    getLocalTimeZone,
    now,
    Time,
    today,
    ZonedDateTime,
} from '@internationalized/date';
import { ChevronDownIcon } from 'lucide-vue-next';
import type { Ref } from 'vue';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';

type Props = {
    minValue?: CalendarDate;
    addTime?: DateTimeDuration;
};

const props = withDefaults(defineProps<Props>(), {
    minValue: () => today(getLocalTimeZone()),
    addTime: () => ({}),
});

const getTime = () =>
    new Time(
        nowObject.value.hour,
        nowObject.value.minute,
        nowObject.value.second,
    );

const nowObject = ref(now(getLocalTimeZone()).add(props.addTime));
const date = ref(props.minValue) as Ref<DateValue>;
const time = ref(getTime()) as Ref<Time>;
const open = ref(false);

const model = defineModel<any>();

let timeInterval: number | undefined = undefined;

const timeString = computed({
    get() {
        return `${String(time.value.hour).padStart(2, '0')}:${String(time.value.minute).padStart(2, '0')}:${String(time.value.second).padStart(2, '0')}`;
    },
    set(val: string) {
        const [h, m, s] = val.split(':').map(Number);

        const selectedDateTime = new ZonedDateTime(
            date.value.year,
            date.value.month,
            date.value.day,
            nowObject.value.timeZone,
            nowObject.value.offset,
            h,
            m,
            s,
        );

        if (selectedDateTime.compare(nowObject.value as ZonedDateTime) < 0) {
            time.value = getTime();

            timeInterval = setInterval(() => {
                time.value = time.value.add({ seconds: 1 });
            }, 1000);
            return;
        }

        time.value = new Time(h, m, s);

        clearInterval(timeInterval);
    },
});

watch(
    [date, time],
    () => {
        model.value = new ZonedDateTime(
            date.value.year,
            date.value.month,
            date.value.day,
            nowObject.value.timeZone,
            nowObject.value.offset,
            time.value.hour,
            time.value.minute,
            time.value.second,
        );
    },
    { deep: true },
);

watch(
    () => date.value,
    () => (timeString.value = time.value.toString()),
    { deep: true },
);

setInterval(() => {
    nowObject.value = nowObject.value.add({ seconds: 1 });
}, 1000);

if (props.minValue.compare(today(getLocalTimeZone())) === 0) {
    timeInterval = setInterval(() => {
        time.value = time.value.add({ seconds: 1 });
    }, 1000);
}
</script>

<template>
    <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-3">
            <Label for="date-picker" class="px-1"> Date </Label>
            <Popover v-model:open="open">
                <PopoverTrigger as-child>
                    <Button
                        id="date-picker"
                        variant="outline"
                        class="justify-between font-normal"
                    >
                        {{
                            date
                                ? date
                                      .toDate(getLocalTimeZone())
                                      .toLocaleDateString()
                                : 'Select date'
                        }}
                        <ChevronDownIcon />
                    </Button>
                </PopoverTrigger>
                <PopoverContent
                    class="w-auto overflow-hidden p-0"
                    align="start"
                >
                    <Calendar
                        :model-value="date"
                        layout="month-and-year"
                        :min-value
                        @update:model-value="
                            (value) => {
                                if (value) {
                                    date = value;
                                    open = false;
                                }
                            }
                        "
                    />
                </PopoverContent>
            </Popover>
        </div>
        <div class="flex flex-col gap-3">
            <Label for="time-picker" class="px-1"> Time </Label>
            <Input
                id="time-picker"
                type="time"
                step="1"
                v-model="timeString"
                class="appearance-none bg-background [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
            />
        </div>
    </div>
</template>
