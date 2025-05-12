<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    whatsapp: ''
});

const validateWhatsapp = () => {
    const whatsappRegex = /^\(\d{2}\) \d{4,5}-\d{4}$/;
    if (!whatsappRegex.test(form.whatsapp)) {
        form.errors.whatsapp = 'O número de WhatsApp deve estar no formato (13) XXXXX-XXXX.';
    } else {
        form.errors.whatsapp = null;
    }
};

const cleanWhatsapp = () => {
    return form.whatsapp.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
};

const submit = () => {
    const cleanedWhatsapp = cleanWhatsapp();
    form.whatsapp = cleanedWhatsapp;

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crie sua conta" description="Entre com suas informações para criar a conta">
        <Head title="Registrar" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nome</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Nome Completo" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">E-mail</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="whatsapp">WhatsApp</Label>
                    <Input
                        id="whatsapp"
                        type="text"
                        required
                        :tabindex="2"
                        autocomplete="text"
                        v-model="form.whatsapp"
                        placeholder="(13) XXXXX-XXXX"
                        @blur="validateWhatsapp"
                    />
                    <InputError :message="form.errors.whatsapp" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Senha</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Senha"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmar senha</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirmar Senha"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Criar Conta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Você já tem uma conta?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
