<script setup lang="ts">
import Navigation from '@/components/Navigation.vue'
import Footer from '@/components/Footer.vue'
import PageLoader from '@/components/PageLoader.vue'
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white font-sans antialiased">
        <PageLoader />
        <Navigation />

        <main class="flex-1">
            <slot />
        </main>

        <Footer />
    </div>
</template>
