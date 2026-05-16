<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue'
import { formatPrice } from '@/lib/utils'

export type OrderSummaryItem = {
    product_name: string
    brand_name: string
    size_label: string | null
    quantity: number
    unit_price: number
    total_price: number
}

interface Props {
    items: OrderSummaryItem[]
    subtotal?: number
    shipping?: number
    total?: number
    orderNumber?: string
    createdAt?: string
    status?: string
    showMetadata?: boolean
}

withDefaults(defineProps<Props>(), {
    showMetadata: false,
})
</script>

<template>
    <div class="border border-gray-100 bg-gray-50 p-8 h-fit">
        <!-- Metadata -->
        <template v-if="showMetadata && orderNumber">
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Broj porudžbine</span>
                <span class="text-sm font-medium text-gray-900">{{ orderNumber }}</span>
            </div>
            <div v-if="createdAt" class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Datum</span>
                <span class="text-sm text-gray-900">{{ createdAt }}</span>
            </div>
            <div v-if="status" class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Status</span>
                <StatusBadge :status="status" />
            </div>
            <div v-if="shipping !== undefined" class="flex items-center justify-between border-b border-gray-200 py-4">
                <span class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Dostava</span>
                <span class="text-sm text-gray-900">{{ formatPrice(shipping) }}</span>
            </div>
        </template>

        <!-- Items -->
        <h2
            v-if="!showMetadata"
            class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase"
        >
            Porudžbina
        </h2>

        <div class="mt-6 space-y-6">
            <div
                v-for="item in items"
                :key="item.product_name + (item.size_label ?? '')"
                class="flex justify-between text-sm"
            >
                <div>
                    <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                    <p class="text-gray-500">
                        {{ item.brand_name }} — {{ item.size_label }} × {{ item.quantity }}
                    </p>
                </div>
                <p class="font-medium text-gray-900">{{ formatPrice(item.total_price) }}</p>
            </div>
        </div>

        <!-- Totals -->
        <div v-if="subtotal !== undefined && shipping !== undefined" class="mt-8 space-y-3 border-t border-gray-200 pt-6">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Međuzbir</span>
                <span>{{ formatPrice(subtotal) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Dostava</span>
                <span>{{ formatPrice(shipping) }}</span>
            </div>
        </div>

        <div v-if="total !== undefined" class="mt-6 border-t border-gray-200 pt-6">
            <div class="flex justify-between text-base font-medium text-gray-900">
                <span>Ukupno</span>
                <span>{{ formatPrice(total) }}</span>
            </div>
        </div>
    </div>
</template>
