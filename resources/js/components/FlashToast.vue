<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3'
import { onUnmounted, ref, watch } from 'vue'

type ToastType = 'success' | 'error' | 'info'

const visible = ref(false)
const message = ref('')
const type = ref<ToastType>('info')
const progress = ref(100)
let timer: ReturnType<typeof setTimeout> | null = null
let progressInterval: ReturnType<typeof setInterval> | null = null

const colors: Record<ToastType, { bg: string; bar: string }> = {
    success: { bg: 'bg-emerald-600', bar: 'bg-emerald-800' },
    error: { bg: 'bg-red-600', bar: 'bg-red-800' },
    info: { bg: 'bg-blue-600', bar: 'bg-blue-800' },
}

function show(msg: string, toastType: ToastType = 'info') {
    if (timer) clearTimeout(timer)
    if (progressInterval) clearInterval(progressInterval)

    message.value = msg
    type.value = toastType
    progress.value = 100
    visible.value = true

    const duration = 3500
    const interval = 30
    const step = 100 / (duration / interval)

    progressInterval = setInterval(() => {
        progress.value = Math.max(0, progress.value - step)
    }, interval)

    timer = setTimeout(() => {
        visible.value = false
        if (progressInterval) clearInterval(progressInterval)
    }, duration)
}

watch(() => usePage().props.flash as { success?: string; error?: string; info?: string } | undefined, (flash) => {
    if (flash?.success) {
        show(flash.success, 'success')
    } else if (flash?.error) {
        show(flash.error, 'error')
    } else if (flash?.info) {
        show(flash.info, 'info')
    }
}, { immediate: true })

const unsubFlash = router.on('flash', (event) => {
    const flash = event.detail.flash as { success?: string; error?: string; info?: string } | undefined
    if (flash?.success) {
        show(flash.success, 'success')
    } else if (flash?.error) {
        show(flash.error, 'error')
    } else if (flash?.info) {
        show(flash.info, 'info')
    }
})

onUnmounted(() => {
    unsubFlash()
    if (timer) clearTimeout(timer)
    if (progressInterval) clearInterval(progressInterval)
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
            class="fixed bottom-8 left-1/2 z-[60] w-[90vw] max-w-md -translate-x-1/2 overflow-hidden rounded-lg shadow-lg"
            :class="colors[type].bg"
        >
            <div class="px-8 py-4 text-center text-base font-medium tracking-wide text-white">
                {{ message }}
            </div>
            <div
                class="h-1 transition-none"
                :class="colors[type].bar"
                :style="{ width: progress + '%' }"
            />
        </div>
    </Transition>
</template>
