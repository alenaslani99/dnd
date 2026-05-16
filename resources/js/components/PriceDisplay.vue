<script setup lang="ts">
import { formatPrice } from '@/lib/utils'

interface Props {
    price: number | null
    salePrice?: number | null
    size?: 'default' | 'small'
}

withDefaults(defineProps<Props>(), {
    size: 'default',
})

const sizeClasses = {
    default: 'text-xl sm:text-2xl',
    small: 'text-sm',
}
</script>

<template>
    <div class="flex items-center gap-3">
        <p
            v-if="salePrice"
            class="font-medium text-red-600"
            :class="sizeClasses[size]"
        >
            {{ formatPrice(salePrice) }}
        </p>
        <p
            class="font-medium"
            :class="[
                sizeClasses[size],
                salePrice ? 'text-gray-400 line-through text-base sm:text-lg' : 'text-gray-900',
            ]"
        >
            {{ formatPrice(price) }}
        </p>
    </div>
</template>
