<script setup lang="ts">
interface Props {
    price: string
    salePrice?: string
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
            {{ salePrice }}
        </p>
        <p
            class="font-medium"
            :class="[
                sizeClasses[size],
                salePrice ? 'text-gray-400 line-through text-base sm:text-lg' : 'text-gray-900',
            ]"
        >
            {{ price }}
        </p>
    </div>
</template>
