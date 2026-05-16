<script setup lang="ts">
interface Props {
    maxWidth?: 'default' | 'narrow' | 'wide'
    padding?: 'default' | 'small' | 'product'
}

withDefaults(defineProps<Props>(), {
    maxWidth: 'default',
    padding: 'default',
})

const maxWidthClasses = {
    default: 'max-w-7xl',
    narrow: 'max-w-2xl',
    wide: 'max-w-7xl',
}

const paddingClasses = {
    default: 'px-6 py-24 lg:px-8',
    small: 'px-6 py-12 lg:px-8',
    product: 'px-4 py-8 sm:px-6 sm:py-16 lg:px-8',
}
</script>

<template>
    <section :class="['mx-auto', maxWidthClasses[maxWidth], paddingClasses[padding]]">
        <slot />
    </section>
</template>
