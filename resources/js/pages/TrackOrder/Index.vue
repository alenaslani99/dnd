<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import OrderSummary from '@/components/OrderSummary.vue'
import PageContainer from '@/components/PageContainer.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import SectionHeader from '@/components/SectionHeader.vue'
import type { Order } from '@/types'
import trackOrderRoutes from '@/routes/track-order'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    order: Order | null
    searched: boolean
    verified_email?: string
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
</script>

<template>
    <Head title="Prati porudžbinu" />

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
        <OrderSummary
            v-if="searched && order"
            class="mt-16"
            :items="order.items"
            :shipping="order.shipping_cost"
            :total="order.total_amount"
            :order-number="order.order_number"
            :created-at="order.created_at"
            :status="order.status"
            show-metadata
        />

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
