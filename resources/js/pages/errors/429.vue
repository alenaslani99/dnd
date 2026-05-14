<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { home } from '@/routes'
</script>

<template>
    <Head title="Previše zahteva" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-white px-6">
        <p class="text-xs font-medium tracking-[0.3em] text-gray-400 uppercase">
            dndparfems
        </p>

        <h1 class="mt-8 font-serif text-8xl font-medium tracking-tight text-gray-900 lg:text-9xl">
            429
        </h1>

        <p class="mt-6 text-base text-gray-500">
            Previše zahteva. Molimo sačekajte pre sledećeg pokušaja.
        </p>

        <Link
            :href="home.url()"
            class="mt-10 inline-block border border-gray-900 bg-gray-900 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
        >
            Početna strana
        </Link>
    </div>
</template>
