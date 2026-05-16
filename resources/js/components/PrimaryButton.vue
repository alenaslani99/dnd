<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Props {
    as?: 'button' | 'link'
    variant?: 'full' | 'inline'
    size?: 'default' | 'small'
    loading?: boolean
    loadingText?: string
    href?: string
    type?: 'button' | 'submit'
    disabled?: boolean
}

withDefaults(defineProps<Props>(), {
    as: 'button',
    variant: 'full',
    size: 'default',
    loading: false,
    loadingText: 'Obrada...',
    type: 'submit',
})

const baseClasses =
    'border border-gray-900 bg-gray-900 text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50'

const variantClasses = {
    full: 'w-full block text-center',
    inline: 'inline-block',
}

const sizeClasses = {
    default: 'px-8 py-4 text-sm font-medium tracking-[0.2em]',
    small: 'px-6 py-3 text-xs font-medium tracking-[0.2em]',
}
</script>

<template>
    <Link
        v-if="as === 'link' && href"
        :href="href"
        :class="[baseClasses, variantClasses[variant], sizeClasses[size]]"
    >
        <slot />
    </Link>
    <button
        v-else
        :type="type"
        :disabled="disabled || loading"
        :class="[baseClasses, variantClasses[variant], sizeClasses[size]]"
        @click="$emit('click', $event)"
    >
        {{ loading ? loadingText : ($slots.default ? undefined : '') }}
        <slot v-if="!loading" />
    </button>
</template>
