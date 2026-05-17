<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue'

interface Props {
    open: boolean
    title: string
    message?: string
    confirmLabel?: string
    cancelLabel?: string
    showCancel?: boolean
    icon?: 'check' | 'alert' | 'info' | null
}

const props = withDefaults(defineProps<Props>(), {
    message: '',
    confirmLabel: 'Potvrdi',
    cancelLabel: 'Otkaži',
    showCancel: true,
    icon: null,
})

const emit = defineEmits<{
    (e: 'confirm'): void
    (e: 'cancel'): void
    (e: 'close'): void
}>()

const isMounted = ref(false)

function onKeydown(event: KeyboardEvent) {
    if (event.key === 'Escape') {
        emit('close')
    }
}

watch(() => props.open, (isOpen) => {
    if (isOpen) {
        document.addEventListener('keydown', onKeydown)
    } else {
        document.removeEventListener('keydown', onKeydown)
    }
})

onMounted(() => {
    isMounted.value = true
})

onUnmounted(() => {
    document.removeEventListener('keydown', onKeydown)
})
</script>

<template>
    <Teleport v-if="isMounted" to="body">
        <Transition name="dialog">
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                @click.self="emit('close')"
            >
                <div class="mx-4 w-full max-w-sm border border-gray-200 bg-white p-8 shadow-2xl">
                    <!-- Header -->
                    <div class="flex items-start justify-between">
                        <div v-if="icon" class="mb-4 flex h-12 w-12 items-center justify-center rounded-full"
                            :class="icon === 'check' ? 'bg-green-50 text-green-600' : icon === 'alert' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-600'"
                        >
                            <svg v-if="icon === 'check'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <svg v-else-if="icon === 'alert'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                        </div>
                        <button
                            type="button"
                            class="ml-auto text-gray-400 transition-colors hover:text-gray-900"
                            @click="emit('close')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <h2 class="font-serif text-xl font-medium tracking-wide text-gray-900">
                        {{ title }}
                    </h2>
                    <p v-if="message" class="mt-2 text-sm leading-relaxed text-gray-500">
                        {{ message }}
                    </p>

                    <!-- Actions -->
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button
                            v-if="showCancel"
                            type="button"
                            class="border border-gray-200 px-6 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase transition-colors hover:border-gray-900 hover:text-gray-900"
                            @click="emit('cancel')"
                        >
                            {{ cancelLabel }}
                        </button>
                        <button
                            type="button"
                            class="border border-gray-900 bg-gray-900 px-6 py-2.5 text-xs font-medium tracking-[0.1em] text-white uppercase transition-colors hover:bg-white hover:text-gray-900"
                            @click="emit('confirm')"
                        >
                            {{ confirmLabel }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.dialog-enter-active,
.dialog-leave-active {
    transition: opacity 250ms ease;
}

.dialog-enter-from,
.dialog-leave-to {
    opacity: 0;
}

.dialog-enter-active > div,
.dialog-leave-active > div {
    transition: transform 250ms ease, opacity 250ms ease;
}

.dialog-enter-from > div,
.dialog-leave-to > div {
    transform: scale(0.97);
    opacity: 0;
}
</style>
