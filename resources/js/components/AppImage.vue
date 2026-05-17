<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    src: string
    alt: string
    sizes?: string
    priority?: boolean
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    sizes: '100vw',
    priority: false,
})

const isSvg = computed(() => {
    return /\.svg$/i.test(props.src)
})

const basePath = computed(() => {
    return props.src.replace(/\.\w+$/, '')
})

const srcset = computed(() => {
    if (isSvg.value) {
        return undefined
    }

    return [
        `${basePath.value}_sm.webp 400w`,
        `${basePath.value}_md.webp 800w`,
        `${basePath.value}_lg.webp 1200w`,
        `${props.src} 2000w`,
    ].join(', ')
})
</script>

<template>
    <img
        :src="src"
        :srcset="srcset"
        :sizes="sizes"
        :alt="alt"
        :loading="priority ? undefined : 'lazy'"
        :fetchpriority="priority ? 'high' : undefined"
        decoding="async"
        :class="$props.class"
    />
</template>
