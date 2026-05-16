<script setup lang="ts">
const model = defineModel<string>({ required: true })

defineProps<{
    label: string
    error?: string
    type?: string
    maxlength?: string | number
    pattern?: string
    inputmode?: 'text' | 'tel' | 'url' | 'email' | 'numeric' | 'decimal' | 'search' | undefined
    placeholder?: string
    required?: boolean
}>()
</script>

<template>
    <div>
        <label class="mb-2 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
            {{ label }}
        </label>
        <input
            v-model="model"
            :type="type ?? 'text'"
            :maxlength="maxlength"
            :pattern="pattern"
            :inputmode="inputmode"
            :placeholder="placeholder"
            :required="required"
            class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
            :class="{ 'border-red-500': error }"
        />
        <p v-if="error" class="mt-2 text-xs text-red-500">{{ error }}</p>
    </div>
</template>
