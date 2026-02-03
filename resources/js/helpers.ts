import { CalendarDate, CalendarDateTime, ZonedDateTime, getLocalTimeZone } from '@internationalized/date';
import { ref } from 'vue';
import {parseDate} from '@internationalized/date';


export const formatDate = (date: CalendarDate, opts :Intl.DateTimeFormatOptions = {weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' }) =>
        new Intl.DateTimeFormat('pt-PT', opts).format(date.toDate(getLocalTimeZone()));

export  const formatDateTime = (date: CalendarDateTime |ZonedDateTime, opts :Intl.DateTimeFormatOptions =
{ weekday: 'long',day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) =>
    new Intl.DateTimeFormat('pt-PT', opts).format(date.toDate(getLocalTimeZone()));
