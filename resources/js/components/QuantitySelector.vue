<script setup lang="ts">
interface Props {
    quantity: number
    min?: number
    max?: number
}

const props = withDefaults(defineProps<Props>(), {
    min: 1,
    max: 10,
})

const emit = defineEmits<{
    (e: 'update', quantity: number): void
}>()

function decrement() {
    if (props.quantity > props.min) {
        emit('update', props.quantity - 1)
    }
}

function increment() {
    if (props.quantity < props.max) {
        emit('update', props.quantity + 1)
    }
}
</script>

<template>
    <div class="inline-flex w-fit items-center border border-gray-200">
        <button
            type="button"
            @click="decrement"
            class="px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-50"
        >
            −
        </button>
        <span class="w-8 px-1 py-2 text-center text-sm font-medium text-gray-900">
            {{ quantity }}
        </span>
        <button
            type="button"
            @click="increment"
            class="px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-50"
        >
            +
        </button>
    </div>
</template>
