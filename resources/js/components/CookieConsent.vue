<script setup lang="ts">
import { ref, onMounted } from 'vue'

const visible = ref(false)

onMounted(() => {
    visible.value = !localStorage.getItem('cookie-consent')
})

function accept() {
    localStorage.setItem('cookie-consent', 'accepted')
    visible.value = false
}
</script>

<template>
    <Transition name="cookie">
        <div
            v-if="visible"
            class="fixed inset-x-0 bottom-0 z-50 border-t border-gray-100 bg-white/95 backdrop-blur-sm"
        >
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 lg:px-8">
                <p class="text-xs leading-relaxed text-gray-500">
                    Ova prodavnica koristi osnovne kolačiće radi boljeg rada. Korišćenjem sajta prihvataš njihovu upotrebu.
                </p>
                <div class="flex shrink-0 items-center gap-4">
                    <a
                        href="/privatnost"
                        class="text-[11px] font-medium tracking-[0.1em] text-gray-400 uppercase transition-colors hover:text-gray-900"
                    >
                        Saznaj više
                    </a>
                    <button
                        type="button"
                        class="cursor-pointer bg-gray-900 px-5 py-2 text-[11px] font-medium tracking-[0.1em] text-white uppercase transition-colors hover:bg-gray-700"
                        @click="accept"
                    >
                        Prihvati
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.cookie-enter-active {
    transition: transform 400ms ease-out, opacity 400ms ease-out;
}

.cookie-leave-active {
    transition: transform 300ms ease-in, opacity 300ms ease-in;
}

.cookie-enter-from {
    transform: translateY(100%);
    opacity: 0;
}

.cookie-leave-to {
    transform: translateY(100%);
    opacity: 0;
}
</style>
