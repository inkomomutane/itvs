import { formatDistanceStrict,setDefaultOptions, } from 'date-fns';
import {pt} from "date-fns/locale";
setDefaultOptions({ locale: pt })

export function formatDateDifferenceString(startDate?: Date, endDate?: Date): string {

    if (!startDate){
        return "";
    }

    if (!endDate) {
        return 'presente';
    }

    return formatDistanceStrict(startDate, endDate, { addSuffix: false });
}
