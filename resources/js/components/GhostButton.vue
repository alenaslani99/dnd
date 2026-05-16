<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Props {
    as?: 'button' | 'link'
    href?: string
    type?: 'button' | 'submit'
}

withDefaults(defineProps<Props>(), {
    as: 'button',
    type: 'button',
})
</script>

<template>
    <Link
        v-if="as === 'link' && href"
        :href="href"
        class="block w-full px-4 py-4 text-center text-sm font-medium tracking-[0.1em] text-gray-500 uppercase transition-colors hover:text-gray-900"
    >
        <slot />
    </Link>
    <button
        v-else
        :type="type"
        class="block w-full px-4 py-4 text-center text-sm font-medium tracking-[0.1em] text-gray-500 uppercase transition-colors hover:text-gray-900"
    >
        <slot />
    </button>
</template>
