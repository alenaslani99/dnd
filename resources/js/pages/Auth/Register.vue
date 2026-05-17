<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { login, register } from '@/routes'

defineOptions({ layout: undefined })

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
})

function submit() {
    form.post(register.url(), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head title="Registracija" />

    <AuthLayout
        title="Registracija"
        subtitle="Kreiraj nalog i otkrij luksuzne mirise."
    >
        <form @submit.prevent="submit" class="space-y-8">
            <BaseInput
                v-model="form.name"
                label="Ime i prezime"
                required
                :error="form.errors.name"
            />

            <BaseInput
                v-model="form.email"
                label="Email adresa"
                type="email"
                required
                :error="form.errors.email"
            />

            <BaseInput
                v-model="form.phone"
                label="Telefon"
                type="tel"
                required
                :error="form.errors.phone"
            />

            <BaseInput
                v-model="form.password"
                label="Lozinka"
                type="password"
                required
                :error="form.errors.password"
            />

            <BaseInput
                v-model="form.password_confirmation"
                label="Potvrdi lozinku"
                type="password"
                required
            />

            <PrimaryButton :loading="form.processing" loading-text="Registrovanje...">
                Registruj se
            </PrimaryButton>
        </form>

        <template #footer>
            Već imaš nalog?
            <Link :href="login.url()" class="ml-1 font-medium text-gray-900 underline-offset-4 hover:underline">
                Prijavi se
            </Link>
        </template>
    </AuthLayout>
</template>
