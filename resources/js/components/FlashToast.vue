<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3'
import { onUnmounted, ref, watch } from 'vue'

const visible = ref(false)
const message = ref('')
const isError = ref(false)
let timer: ReturnType<typeof setTimeout> | null = null

function show(msg: string, error = false) {
    if (timer) clearTimeout(timer)
    message.value = msg
    isError.value = error
    visible.value = true
    timer = setTimeout(() => {
        visible.value = false
    }, 3500)
}

watch(() => usePage().props.flash as { success?: string; error?: string } | undefined, (flash) => {
    if (flash?.success) {
        show(flash.success, false)
    } else if (flash?.error) {
        show(flash.error, true)
    }
}, { immediate: true })

const unsubFlash = router.on('flash', (event) => {
    const flash = event.detail.flash as { success?: string; error?: string } | undefined
    if (flash?.success) {
        show(flash.success, false)
    } else if (flash?.error) {
        show(flash.error, true)
    }
})

onUnmounted(() => {
    unsubFlash()
    if (timer) clearTimeout(timer)
})
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="translate-y-4 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-4 opacity-0"
    >
        <div
            v-if="visible"
            class="fixed bottom-8 left-1/2 z-[60] -translate-x-1/2 px-6 py-3 text-sm font-medium tracking-wide shadow-lg"
            :class="isError ? 'bg-red-600 text-white' : 'bg-gray-900 text-white'"
        >
            {{ message }}
        </div>
    </Transition>
</template>
