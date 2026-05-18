<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import PageContainer from '@/components/PageContainer.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import SectionHeader from '@/components/SectionHeader.vue'
import StatusBadge from '@/components/StatusBadge.vue'
import trackOrderRoutes from '@/routes/track-order'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    order: {
        order_number: string
        status: string
        created_at: string
    } | null
    searched: boolean
}>()

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { email: string } | null)

const form = useForm({
    order_number: '',
    email: authUser.value?.email ?? '',
})

function submit() {
    form.post(trackOrderRoutes.store.url(), {
        preserveScroll: true,
    })
}

const faqSchemaString = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: [
        {
            '@type': 'Question',
            name: 'Kako mogu da pratim svoju porudžbinu?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Unesite broj porudžbine i email adresu koju ste koristili prilikom kupovine u formu iznad. Nakon toga će vam biti prikazan trenutni status porudžbine.',
            },
        },
        {
            '@type': 'Question',
            name: 'Gde mogu da nađem broj porudžbine?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Broj porudžbine se nalazi u emailu koji ste dobili nakon uspešne kupovine. Takođe je prikazan na stranici sa potvrdom porudžbine.',
            },
        },
        {
            '@type': 'Question',
            name: 'Koliko dugo traje isporuka?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Isporuka traje 2-5 radnih dana za celu teritoriju Srbije. Dostavu vršimo putem kurirske službe.',
            },
        },
        {
            '@type': 'Question',
            name: 'Šta ako moja porudžbina kasni?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Ako porudžbina ne stigne u roku od 5 radnih dana, kontaktirajte nas na info@dndparfems.rs i proverićemo status isporuke.',
            },
        },
    ],
})
</script>

<template>
    <Head title="Prati porudžbinu — dndparfems">
        <meta name="description" content="Prati status svoje porudžbine na dndparfems. Unesi broj porudžbine i email adresu za brzu proveru." />
    </Head>

    <component :is="'script'" type="application/ld+json" v-text="faqSchemaString" />

    <PageContainer max-width="narrow">
        <SectionHeader
            title="Prati porudžbinu"
            subtitle="Unesi broj porudžbine i email adresu za verifikaciju."
            margin-bottom="large"
        />

        <!-- Search Form -->
        <form @submit.prevent="submit" class="mx-auto max-w-md space-y-6">
            <BaseInput
                v-model="form.order_number"
                label="Broj porudžbine"
                placeholder="ORD-A7X2K9"
                maxlength="20"
                required
                :error="form.errors.order_number"
            />
            <BaseInput
                v-model="form.email"
                label="Email adresa"
                type="email"
                placeholder="tvoja@email.rs"
                maxlength="255"
                required
                :error="form.errors.email"
            />
            <PrimaryButton :loading="form.processing" loading-text="Tražim...">
                Traži
            </PrimaryButton>
        </form>

        <!-- Order Result -->
        <div v-if="searched && order" class="mt-16 border border-gray-100 bg-gray-50 p-8">
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Broj porudžbine</span>
                <span class="text-sm font-medium text-gray-900">{{ order.order_number }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Datum</span>
                <span class="text-sm text-gray-900">{{ order.created_at }}</span>
            </div>
            <div class="flex items-center justify-between pt-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Status</span>
                <StatusBadge :status="order.status" />
            </div>
        </div>

        <!-- Not Found -->
        <div v-if="searched && !order" class="mt-16 text-center">
            <p class="text-lg text-gray-400">
                Porudžbina sa ovim brojem nije pronađena.
            </p>
            <p class="mt-2 text-sm text-gray-500">
                Proveri da li si tačno uneo broj porudžbine i email adresu.
            </p>
        </div>

        <div class="mt-14 text-center">
            <PrimaryButton
                as="link"
                variant="inline"
                :href="productRoutes.index.url()"
            >
                Nastavi kupovinu
            </PrimaryButton>
        </div>
    </PageContainer>
</template>
