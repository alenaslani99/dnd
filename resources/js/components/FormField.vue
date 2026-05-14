<script setup lang="ts">
defineProps<{
    label: string
    error?: string
    required?: boolean
}>()
</script>

<template>
    <div>
        <label class="mb-2 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
            {{ label }}
        </label>
        <slot />
        <p v-if="error" class="mt-2 text-xs text-red-500">{{ error }}</p>
    </div>
</template>
