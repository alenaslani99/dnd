<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import PageContainer from '@/components/PageContainer.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import SectionHeader from '@/components/SectionHeader.vue'
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

const faqSchemaString = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: [
        {
            '@type': 'Question',
            name: 'Kako mogu da kontaktiram dndparfems?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Možete nas kontaktirati putem emaila na info@dndparfems.rs, telefonom na +381 11 123 4567, ili putem kontakt forme na ovoj stranici.',
            },
        },
        {
            '@type': 'Question',
            name: 'Koje je radno vreme prodavnice?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Radno vreme je ponedeljkom do petkom od 09:00 do 20:00, subotom od 10:00 do 16:00. Nedeljom ne radimo.',
            },
        },
        {
            '@type': 'Question',
            name: 'Gde se nalazite?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Nalazimo se u Beogradu, Srbija. Vršimo dostavu putem kurirske službe na celoj teritoriji Republike Srbije.',
            },
        },
        {
            '@type': 'Question',
            name: 'Kako mogu da vratim proizvod?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Imate pravo na povraćaj u roku od 14 dana od dana prijema pošiljke. Proizvod mora biti neoštećen i u originalnom pakovanju. Kontaktirajte nas putem emaila za više informacija.',
            },
        },
    ],
})
</script>

<template>
    <Head title="Kontakt — dndparfems">
        <meta name="description" content="Kontaktiraj dndparfems. Piši nam na info@dndparfems.rs, pozovi +381 11 123 4567 ili nas poseti u Beogradu." />
    </Head>

    <component :is="'script'" type="application/ld+json" v-text="faqSchemaString" />

    <PageContainer>
        <SectionHeader
            title="Kontakt"
            subtitle="Piši nam. Tu smo da ti pomognemo."
            margin-bottom="large"
        />

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
                <BaseInput
                    v-model="form.name"
                    label="Ime i prezime"
                    required
                    maxlength="255"
                    :error="form.errors.name"
                />

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <BaseInput
                        v-model="form.email"
                        label="Email"
                        type="email"
                        required
                        maxlength="255"
                        :error="form.errors.email"
                    />

                    <BaseInput
                        v-model="form.phone"
                        label="Telefon (opciono)"
                        type="tel"
                        maxlength="20"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Poruka
                    </label>
                    <textarea
                        v-model="form.message"
                        required
                        rows="5"
                        maxlength="2000"
                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900 resize-none"
                        :class="{ 'border-red-500': form.errors.message }"
                    />
                    <p v-if="form.errors.message" class="mt-2 text-xs text-red-500">{{ form.errors.message }}</p>
                </div>

                <PrimaryButton :loading="form.processing" loading-text="Slanje...">
                    Pošalji poruku
                </PrimaryButton>
            </form>
        </div>
    </PageContainer>
</template>
