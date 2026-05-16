<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import OrderSummary from '@/components/OrderSummary.vue'
import PageContainer from '@/components/PageContainer.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import type { Order } from '@/types'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

defineProps<{
    order: Order
}>()
</script>

<template>
    <Head :title="`Porudžbina ${order.order_number}`" />

    <PageContainer max-width="narrow">
        <div class="text-center">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-900"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h1 class="font-serif text-3xl font-medium tracking-wide text-gray-900">
                Hvala na porudžbini
            </h1>
            <p class="mt-3 text-sm text-gray-500">
                Tvoja porudžbina <span class="font-medium text-gray-900">{{ order.order_number }}</span> je uspešno primljena.
            </p>
        </div>

        <OrderSummary
            class="mt-14"
            :items="order.items"
            :shipping="order.shipping_cost"
            :total="order.total_amount"
            :order-number="order.order_number"
            :created-at="order.created_at"
            :status="order.status"
            show-metadata
        />

        <div class="mt-10 text-center">
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
