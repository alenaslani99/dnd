<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Prijava" />

    <div class="flex min-h-screen items-center justify-center bg-white px-6">
        <div class="w-full max-w-sm">
            <div class="mb-12 text-center">
                <Link href="/" class="mb-6 inline-block font-sans text-xl font-semibold tracking-[0.25em] text-gray-900 uppercase">
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
                <div>
                    <label for="email" class="mb-2 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Email adresa
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                        :class="{ 'border-red-500': form.errors.email }"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-xs text-red-500">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Lozinka
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                        :class="{ 'border-red-500': form.errors.password }"
                    />
                    <p v-if="form.errors.password" class="mt-2 text-xs text-red-500">
                        {{ form.errors.password }}
                    </p>
                </div>

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

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full border border-gray-900 bg-gray-900 px-8 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50"
                >
                    {{ form.processing ? 'Prijavljivanje...' : 'Prijavi se' }}
                </button>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Nemaš nalog?
                <Link href="/register" class="ml-1 font-medium text-gray-900 underline-offset-4 hover:underline">
                    Registruj se
                </Link>
            </p>
        </div>
    </div>
</template>
