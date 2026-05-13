<script setup lang="ts">
defineProps<{
    image: string
    brand: string
    name: string
    price: string
}>()
</script>

<template>
    <div class="group cursor-pointer">
        <div class="aspect-[3/4] overflow-hidden bg-gray-100">
            <img
                :src="image"
                :alt="name"
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
        </div>
        <div class="mt-4">
            <p class="text-xs font-medium tracking-widest text-gray-500 uppercase">
                {{ brand }}
            </p>
            <h3 class="mt-1 text-sm font-medium text-gray-900">
                {{ name }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ price }}
            </p>
        </div>
    </div>
</template>
