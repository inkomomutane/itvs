import { DateTime } from 'luxon';
import { parseDate } from '@internationalized/date';

// Supported formats
const formats = [
    'dd.MM.yyyy','d.MM.yyyy','dd.M.yyyy','d.M.yyyy',
    'dd/MM/yyyy','d/MM/yyyy','dd/M/yyyy','d/M/yyyy',
    'M-d-yyyy','M-d-yyyy','MM-dd-yyyy','MM-dd-yyyy',
    'dd MMM yyyy','d MMM yyyy','dd MMMM yyyy','d MMMM yyyy',
];

export const  parseToCalendarDate = (input: string) =>  {
    if (!input || input.trim() === '') {
        return null;
    }
    for (const fmt of formats) {
        const dt = DateTime.fromFormat(input.trim(), fmt, { locale: 'en-GB' });
        if (dt.isValid) {
            return parseDate(dt.toISODate()); // ISO date to CalendarDate
        }
    }
    return null;
}
