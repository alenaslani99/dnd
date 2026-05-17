<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppImage from '@/components/AppImage.vue'
import QuantitySelector from '@/components/QuantitySelector.vue'
import { formatPrice } from '@/lib/utils'

interface Props {
    item: {
        id: number
        quantity: number
        variant: { id: number; size_label: string | null; sku: string }
        product: { slug: string; name: string; brand: string; image: string }
        unit_price: number | null
        total_price: number | null
    }
}

const props = defineProps<Props>()

const emit = defineEmits<{
    (e: 'update-quantity', itemId: number, quantity: number): void
    (e: 'remove', itemId: number): void
}>()

function updateQuantity(qty: number) {
    emit('update-quantity', props.item.id, qty)
}

function remove() {
    emit('remove', props.item.id)
}
</script>

<template>
    <div class="flex gap-6 border-b border-gray-100 pb-8">
        <Link :href="`/parfemi/${item.product.slug}`" class="shrink-0">
            <div class="h-32 w-24 overflow-hidden bg-gray-100">
                <AppImage
                    :src="item.product.image"
                    :alt="item.product.name"
                    sizes="96px"
                    class="h-full w-full object-cover"
                />
            </div>
        </Link>

        <div class="flex flex-1 flex-col justify-between min-w-0">
            <div>
                <p class="text-[11px] font-medium tracking-[0.2em] text-gray-500 uppercase">
                    {{ item.product.brand }}
                </p>
                <Link
                    :href="`/parfemi/${item.product.slug}`"
                    class="mt-1 block text-base font-medium text-gray-900 break-words"
                >
                    {{ item.product.name }}
                </Link>
                <p class="mt-1 text-sm text-gray-400">
                    {{ item.variant.size_label }}
                </p>
            </div>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <QuantitySelector
                    :quantity="item.quantity"
                    @update="updateQuantity"
                />

                <div class="flex items-center justify-between gap-4 sm:justify-end">
                    <p class="text-sm font-medium text-gray-900">
                        {{ formatPrice(item.total_price) }}
                    </p>
                    <button
                        type="button"
                        @click="remove"
                        class="text-xs text-gray-400 underline-offset-4 transition-colors hover:text-red-600 hover:underline"
                    >
                        Ukloni
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
