<script setup lang="ts">
import { ref, watch } from 'vue'

const model = defineModel<string>({ required: true })

const PREFIX = '+381 '

function extractDigits(value: string): string {
    let digits = value.replace(/[^0-9]/g, '')
    if (digits.startsWith('381')) {
        digits = digits.slice(3)
    }
    return digits.slice(0, 9)
}

const numberPart = ref(extractDigits(model.value))

watch(() => model.value, (newValue) => {
    numberPart.value = extractDigits(newValue)
})

function onInput(event: Event) {
    const input = event.target as HTMLInputElement
    let raw = input.value.replace(/[^0-9]/g, '').slice(0, 9)
    numberPart.value = raw
    model.value = PREFIX + raw
}
</script>

<template>
    <div
        class="flex w-full items-center border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus-within:border-gray-900"
    >
        <span class="select-none text-gray-500">{{ PREFIX.trim() }}</span>
        <input
            :value="numberPart"
            type="tel"
            required
            maxlength="9"
            @input="onInput"
            class="ml-1 w-full bg-transparent outline-none"
        />
    </div>
</template>
