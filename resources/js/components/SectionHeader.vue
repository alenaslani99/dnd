<script setup lang="ts">
interface Props {
    title: string
    subtitle?: string
    marginBottom?: 'default' | 'large'
}

withDefaults(defineProps<Props>(), {
    marginBottom: 'default',
})

const marginClasses = {
    default: 'mb-12',
    large: 'mb-16',
}
</script>

<template>
    <div :class="['text-center', marginClasses[marginBottom]]">
        <h1 class="font-serif text-4xl font-medium tracking-wide text-gray-900 lg:text-5xl">
            {{ title }}
        </h1>
        <p v-if="subtitle" class="mt-4 text-base text-gray-500">
            {{ subtitle }}
        </p>
    </div>
</template>
