<script setup lang="ts">
import { formatPrice } from '@/lib/utils'

export type OrderListItem = {
    product_name: string
    brand_name: string
    size_label: string | null
    quantity: number
    unit_price: number
    total_price: number
}

interface Props {
    items: OrderListItem[]
}

defineProps<Props>()
</script>

<template>
    <div class="mt-6 space-y-4">
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
</template>
