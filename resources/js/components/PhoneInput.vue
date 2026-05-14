<script setup lang="ts">
const model = defineModel<string>({ required: true })

const PREFIX = '+381 '

function onInput(event: Event) {
    const input = event.target as HTMLInputElement
    let raw = input.value

    if (!raw.startsWith(PREFIX)) {
        raw = PREFIX
    }

    const numberPart = raw.slice(PREFIX.length).replace(/[^0-9]/g, '').slice(0, 9)

    model.value = PREFIX + numberPart
}

function onKeydown(event: KeyboardEvent) {
    if (
        event.key === 'Backspace' ||
        event.key === 'Delete'
    ) {
        const input = event.target as HTMLInputElement
        const cursorPos = input.selectionStart ?? 0
        if (cursorPos <= PREFIX.length) {
            event.preventDefault()
        }
    }
}
</script>

<template>
    <input
        :value="model"
        type="tel"
        required
        maxlength="17"
        @input="onInput"
        @keydown="onKeydown"
        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
    />
</template>
