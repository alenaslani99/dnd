<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { home } from '@/routes'
</script>

<template>
    <Head title="Stranica nije pronađena" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-white px-6">
        <p class="text-xs font-medium tracking-[0.3em] text-gray-400 uppercase">
            dndparfems
        </p>

        <h1 class="mt-8 font-serif text-8xl font-medium tracking-tight text-gray-900 lg:text-9xl">
            404
        </h1>

        <p class="mt-6 text-base text-gray-500">
            Stranica koju tražite ne postoji.
        </p>

        <PrimaryButton
            as="link"
            variant="inline"
            :href="home.url()"
            class="mt-10"
        >
            Početna strana
        </PrimaryButton>
    </div>
</template>
