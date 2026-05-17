<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { home } from '@/routes'

interface Props {
    title: string
    subtitle: string
}

defineProps<Props>()
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-white px-6 py-12">
        <div class="w-full max-w-sm">
            <!-- Header -->
            <div class="mb-12 text-center">
                <Link
                    :href="home.url()"
                    class="mb-6 inline-block font-sans text-xl font-semibold tracking-[0.25em] text-gray-900 uppercase"
                >
                    dndparfems
                </Link>
                <h1 class="font-serif text-3xl font-medium tracking-wide text-gray-900">
                    {{ title }}
                </h1>
                <p class="mt-3 text-sm text-gray-500">
                    {{ subtitle }}
                </p>
            </div>

            <!-- Form -->
            <slot />

            <!-- Footer -->
            <div class="mt-10 text-center text-sm text-gray-500">
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>
