<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import BaseInput from '@/components/BaseInput.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { login, register, home } from '@/routes'

defineOptions({ layout: undefined })

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function submit() {
    form.post(login.url(), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Prijava" />

    <div class="flex min-h-screen items-center justify-center bg-white px-6">
        <div class="w-full max-w-sm">
            <div class="mb-12 text-center">
                <Link :href="home.url()" class="mb-6 inline-block font-sans text-xl font-semibold tracking-[0.25em] text-gray-900 uppercase">
                    dndparfems
                </Link>
                <h1 class="font-serif text-3xl font-medium tracking-wide text-gray-900">
                    Prijava
                </h1>
                <p class="mt-3 text-sm text-gray-500">
                    Dobrodošli nazad. Prijavite se da nastavite.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <BaseInput
                    v-model="form.email"
                    label="Email adresa"
                    type="email"
                    required
                    :error="form.errors.email"
                />

                <BaseInput
                    v-model="form.password"
                    label="Lozinka"
                    type="password"
                    required
                    :error="form.errors.password"
                />

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                        />
                        <span class="text-sm text-gray-600">Zapamti me</span>
                    </label>
                </div>

                <PrimaryButton :loading="form.processing" loading-text="Prijavljivanje...">
                    Prijavi se
                </PrimaryButton>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Nemaš nalog?
                <Link :href="register.url()" class="ml-1 font-medium text-gray-900 underline-offset-4 hover:underline">
                    Registruj se
                </Link>
            </p>
        </div>
    </div>
</template>
