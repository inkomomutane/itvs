import { ref } from 'vue';

function DialogContentManager<T>() {
    const trigger = ref(false);
    const attribute = ref<T | null>(null);
    const open = (param: T) => {
        trigger.value = true;
        attribute.value = param;
    };
    const close = () => {
        trigger.value = false;
        attribute.value = null;
    };
    return {
        trigger,
        attribute,
        open,
        close,
    };
}
export { DialogContentManager };
