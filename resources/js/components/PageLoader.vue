<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { onMounted, onUnmounted, ref } from 'vue'

const loading = ref(false)
const progress = ref(0)
let interval: ReturnType<typeof setInterval> | null = null
let timeout: ReturnType<typeof setTimeout> | null = null
let unsubStart: (() => void) | null = null
let unsubProgress: (() => void) | null = null
let unsubFinish: (() => void) | null = null

function clearSimulation() {
    if (interval) {
        clearInterval(interval)
        interval = null
    }
    if (timeout) {
        clearTimeout(timeout)
        timeout = null
    }
}

function simulateProgress() {
    clearSimulation()
    interval = setInterval(() => {
        if (progress.value < 90) {
            progress.value += Math.random() * 8
            if (progress.value > 90) {
                progress.value = 90
            }
        }
    }, 180)
}

onMounted(() => {
    unsubStart = router.on('start', () => {
        loading.value = true
        progress.value = 0
        simulateProgress()
    })

    unsubProgress = router.on('progress', (event) => {
        if (event.detail.progress) {
            progress.value = event.detail.progress.percentage
        }
    })

    unsubFinish = router.on('finish', () => {
        clearSimulation()
        progress.value = 100
        timeout = setTimeout(() => {
            loading.value = false
            progress.value = 0
        }, 400)
    })
})

onUnmounted(() => {
    clearSimulation()
    unsubStart?.()
    unsubProgress?.()
    unsubFinish?.()
})
</script>

<template>
    <Transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="loading"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-[#ededed]"
        >
            <div class="flex items-center gap-2 text-gray-900">
                <span class="text-5xl font-light">(</span>
                <span class="text-xl font-medium tabular-nums">{{ Math.round(progress) }}%</span>
                <span class="text-5xl font-light">)</span>
            </div>
        </div>
    </Transition>
</template>
