<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import StatusBadge from '@/components/StatusBadge.vue'
import { formatPrice } from '@/lib/utils'
import type { Order } from '@/types'
import trackOrderRoutes from '@/routes/track-order'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    order: Order | null
    searched: boolean
}>()

const form = useForm({
    order_number: '',
})

function submit() {
    form.post(trackOrderRoutes.store.url(), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Prati porudžbinu" />

    <section class="mx-auto max-w-2xl px-6 py-24 lg:px-8">
        <div class="mb-14 text-center">
            <h1 class="font-serif text-4xl font-medium tracking-wide text-gray-900 lg:text-5xl">
                Prati porudžbinu
            </h1>
            <p class="mt-4 text-base text-gray-500">
                Unesi broj porudžbine da bi proverio status.
            </p>
        </div>

        <!-- Search Form -->
        <form @submit.prevent="submit" class="mx-auto max-w-md">
            <div class="flex gap-3">
                <input
                    v-model="form.order_number"
                    type="text"
                    required
                    placeholder="ORD-A7X2K9"
                    maxlength="20"
                    class="flex-1 border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                    :class="{ 'border-red-500': form.errors.order_number }"
                />
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="shrink-0 border border-gray-900 bg-gray-900 px-6 py-3 text-xs font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50"
                >
                    {{ form.processing ? 'Tražim...' : 'Traži' }}
                </button>
            </div>
            <p v-if="form.errors.order_number" class="mt-2 text-xs text-red-500">{{ form.errors.order_number }}</p>
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
            <div class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Status</span>
                <StatusBadge :status="order.status" />
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

        <!-- Not Found -->
        <div v-if="searched && !order" class="mt-16 text-center">
            <p class="text-lg text-gray-400">
                Porudžbina sa ovim brojem nije pronađena.
            </p>
            <p class="mt-2 text-sm text-gray-500">
                Proveri da li si tačno uneo broj porudžbine.
            </p>
        </div>

        <div class="mt-14 text-center">
            <Link
                :href="productRoutes.index.url()"
                class="inline-block border border-gray-900 bg-gray-900 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
            >
                Nastavi kupovinu
            </Link>
        </div>
    </section>
</template>
