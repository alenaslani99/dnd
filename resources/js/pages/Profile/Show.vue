<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps<{
    user: {
        name: string
        email: string
        phone: string
    }
}>()
</script>

<template>
    <Head title="Profil" />

    <section class="mx-auto max-w-2xl px-6 py-24 lg:px-8">
        <h1 class="mb-12 text-center font-serif text-4xl font-medium tracking-wide text-gray-900">
            Moj profil
        </h1>

        <div class="border border-gray-100 bg-gray-50 p-8">
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Ime i prezime</p>
                    <p class="mt-1 text-base text-gray-900">{{ user.name }}</p>
                </div>
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Email</p>
                    <p class="mt-1 text-base text-gray-900">{{ user.email }}</p>
                </div>
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Telefon</p>
                    <p class="mt-1 text-base text-gray-900">{{ user.phone }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
