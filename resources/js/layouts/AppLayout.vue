<script setup lang="ts">
import Navigation from '@/components/Navigation.vue'
import Footer from '@/components/Footer.vue'
import PageLoader from '@/components/PageLoader.vue'
import FlashToast from '@/components/FlashToast.vue'
import CookieConsent from '@/components/CookieConsent.vue'
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white font-sans antialiased">
        <PageLoader />
        <FlashToast />
        <Navigation />

        <main class="flex-1">
            <slot />
        </main>

        <Footer />
        <CookieConsent />
    </div>
</template>
