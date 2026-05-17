<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { login, register } from '@/routes'

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

    <AuthLayout
        title="Prijava"
        subtitle="Dobrodošli nazad. Prijavite se da nastavite."
    >
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

        <template #footer>
            Nemaš nalog?
            <Link :href="register.url()" class="ml-1 font-medium text-gray-900 underline-offset-4 hover:underline">
                Registruj se
            </Link>
        </template>
    </AuthLayout>
</template>
