<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { formatPrice, statusLabel } from '@/lib/utils'
import type { Order } from '@/types'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

defineProps<{
    order: Order
}>()
</script>

<template>
    <Head :title="`Porudžbina ${order.order_number}`" />

    <section class="mx-auto max-w-2xl px-6 py-24 lg:px-8">
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

        <div class="mt-14 border border-gray-100 bg-gray-50 p-8">
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Broj porudžbine</span>
                <span class="text-sm font-medium text-gray-900">{{ order.order_number }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Datum</span>
                <span class="text-sm text-gray-900">{{ order.created_at }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Status</span>
                <span class="text-sm font-medium text-gray-900">{{ statusLabel(order.status) }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Dostava</span>
                <span class="text-sm text-gray-900">{{ formatPrice(order.shipping_cost) }}</span>
            </div>

            <div class="mt-6 space-y-4">
                <div
                    v-for="item in order.items"
                    :key="item.product_name + item.size_label"
                    class="flex justify-between text-sm"
                >
                    <div>
                        <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                        <p class="text-gray-500">{{ item.brand_name }} — {{ item.size_label }} × {{ item.quantity }}</p>
                    </div>
                    <p class="font-medium text-gray-900">{{ formatPrice(item.total_price) }}</p>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex justify-between text-base font-medium text-gray-900">
                    <span>Ukupno</span>
                    <span>{{ formatPrice(order.total_amount) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center">
            <Link
                :href="productRoutes.index.url()"
                class="inline-block border border-gray-900 bg-gray-900 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
            >
                Nastavi kupovinu
            </Link>
        </div>
    </section>
</template>
