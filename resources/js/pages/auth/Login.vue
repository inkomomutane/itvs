<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    sap_number: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase :title="$t('Cimentos de Moçambique')" :description="$t('Sistema de Gestão do Refeitório')">
        <Head :title="$t('Log In')" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="sap_number">{{$t('Nome de Utilizador')}}</Label>
                    <Input
                        id="sap_number"
                        type="text"
                        required
                        autofocus
                        class="h-12 px-4 text-base"
                        tabindex="1"
                        autocomplete="sap_number"
                        v-model="form.sap_number"
                        :placeholder="$t('Digite seu utilizador')"
                    />
                    <InputError :message="form.errors.sap_number" />
                </div>

                <div class="grid gap-2">
                    <Input
                        id="password"
                        type="password"

                        required
                        tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        :placeholder="$t('Password')"
                        class="h-12 px-4 text-base"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between" tabindex="3">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" v-model:checked="form.remember" tabindex="4" />
                        <span>{{ $t('Remember') }}</span>
                    </Label>
                </div>

                <Button type="submit" size="lg" class="mt-4 w-full bg-blue-600 hover:bg-glue-500/80  text-base" tabindex="4" :disabled="form.processing"


                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                      {{ $t('Log In') }}
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
