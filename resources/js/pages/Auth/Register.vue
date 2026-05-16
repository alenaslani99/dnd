<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import BaseInput from '@/components/BaseInput.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { login, register, home } from '@/routes'

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

    <div class="flex min-h-screen items-center justify-center bg-white px-6 py-12">
        <div class="w-full max-w-sm">
            <div class="mb-12 text-center">
                <Link :href="home.url()" class="mb-6 inline-block font-sans text-xl font-semibold tracking-[0.25em] text-gray-900 uppercase">
                    dndparfems
                </Link>
                <h1 class="font-serif text-3xl font-medium tracking-wide text-gray-900">
                    Registracija
                </h1>
                <p class="mt-3 text-sm text-gray-500">
                    Kreiraj nalog i otkrij luksuzne mirise.
                </p>
            </div>

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

            <p class="mt-10 text-center text-sm text-gray-500">
                Već imaš nalog?
                <Link :href="login.url()" class="ml-1 font-medium text-gray-900 underline-offset-4 hover:underline">
                    Prijavi se
                </Link>
            </p>
        </div>
    </div>
</template>
