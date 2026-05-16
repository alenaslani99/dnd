<script setup lang="ts">
interface Props {
    label: string
    active?: boolean
}

withDefaults(defineProps<Props>(), {
    active: false,
})

const emit = defineEmits<{
    (e: 'toggle'): void
}>()
</script>

<template>
    <button
        type="button"
        class="border px-4 py-2 text-xs font-medium tracking-wider uppercase transition-all"
        :class="active
            ? 'border-gray-900 bg-gray-900 text-white'
            : 'border-gray-200 bg-white text-gray-700 hover:border-gray-900'
        "
        @click="emit('toggle')"
    >
        {{ label }}
    </button>
</template>
