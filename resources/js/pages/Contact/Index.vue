<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import FormField from '@/components/FormField.vue'
import contactRoutes from '@/routes/contact'

defineOptions({ layout: AppLayout })

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
})

function submit() {
    form.post(contactRoutes.store.url(), {
        onSuccess: () => form.reset(),
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Kontakt" />

    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
        <div class="mb-16 text-center">
            <h1 class="font-serif text-4xl font-medium tracking-wide text-gray-900 lg:text-5xl">
                Kontakt
            </h1>
            <p class="mt-4 text-base text-gray-500">
                Piši nam. Tu smo da ti pomognemo.
            </p>
        </div>

        <div class="mx-auto grid max-w-4xl grid-cols-1 gap-16 lg:grid-cols-2">
            <!-- Contact Info -->
            <div>
                <h2 class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                    Informacije
                </h2>

                <div class="mt-8 space-y-8">
                    <div>
                        <p class="text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
                            Email
                        </p>
                        <p class="mt-2 text-sm text-gray-900">
                            info@dndparfems.rs
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
                            Telefon
                        </p>
                        <p class="mt-2 text-sm text-gray-900">
                            +381 11 123 4567
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
                            Lokacija
                        </p>
                        <p class="mt-2 text-sm text-gray-900">
                            Beograd, Srbija
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
                            Radno vreme
                        </p>
                        <p class="mt-2 text-sm text-gray-900">
                            Pon – Pet: 09:00 – 20:00<br />
                            Sub: 10:00 – 16:00
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <form @submit.prevent="submit" class="space-y-8">
                <FormField label="Ime i prezime" :error="form.errors.name">
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        maxlength="255"
                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                        :class="{ 'border-red-500': form.errors.name }"
                    />
                </FormField>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <FormField label="Email" :error="form.errors.email">
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            maxlength="255"
                            class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                    </FormField>

                    <FormField label="Telefon (opciono)">
                        <input
                            v-model="form.phone"
                            type="tel"
                            maxlength="20"
                            class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                        />
                    </FormField>
                </div>

                <FormField label="Poruka" :error="form.errors.message">
                    <textarea
                        v-model="form.message"
                        required
                        rows="5"
                        maxlength="2000"
                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900 resize-none"
                        :class="{ 'border-red-500': form.errors.message }"
                    />
                </FormField>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full border border-gray-900 bg-gray-900 px-8 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50"
                >
                    {{ form.processing ? 'Slanje...' : 'Pošalji poruku' }}
                </button>
            </form>
        </div>
    </section>
</template>
