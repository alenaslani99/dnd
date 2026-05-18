<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import adminBrandsRoute from '@/routes/admin/brands'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    brands: {
        id: number
        name: string
        slug: string
        logo: string | null
        products_count: number
    }[]
}>()

const showAddModal = ref(false)
const form = useForm<{
    name: string
    logo: File | null
}>({
    name: '',
    logo: null,
})
const logoFileName = ref('')

function onLogoChange(event: Event): void {
    const input = event.target as HTMLInputElement
    const file = input.files?.[0] ?? null
    form.logo = file
    logoFileName.value = file?.name ?? ''
}

function submit(): void {
    form.post(adminBrandsRoute.store.url(), {
        onSuccess: () => {
            form.reset()
            logoFileName.value = ''
            showAddModal.value = false
        },
    })
}
</script>

<template>
    <Head title="Brendovi" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="font-serif text-2xl font-medium tracking-wide text-gray-900 lg:text-3xl">
                Brendovi
            </h1>
            <span class="text-sm text-gray-500">
                {{ brands.length }} ukupno
            </span>
        </div>

        <!-- Toolbar -->
        <div class="flex items-center justify-end">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
                @click="showAddModal = true"
            >
                <Icon name="tag" :size="16" />
                Dodaj brend
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[36rem] text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold tracking-[0.1em] text-gray-500 uppercase">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">ID</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Naziv</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Slug</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Logo</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Proizvoda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="brand in brands"
                            :key="brand.id"
                            class="transition-colors hover:bg-gray-50"
                        >
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-500">#{{ brand.id }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 font-medium text-gray-900">{{ brand.name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ brand.slug }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">
                                <span
                                    :class="[
                                        'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        brand.logo
                                            ? 'bg-green-50 text-green-700'
                                            : 'bg-gray-100 text-gray-500',
                                    ]"
                                >
                                    {{ brand.logo ? 'Postoji' : 'Nema' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ brand.products_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="brands.length === 0" class="py-16 text-center">
            <p class="text-lg text-gray-400">Nema brendova.</p>
        </div>
    </div>

    <!-- Add Brand Modal -->
    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="showAddModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            @click.self="showAddModal = false"
        >
            <div class="w-full max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Novi brend
                    </h3>
                    <button
                        type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-md text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        @click="showAddModal = false"
                    >
                        <Icon name="close" :size="16" />
                    </button>
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="brand-name" class="mb-2 block text-sm font-medium text-gray-700">
                            Naziv brenda
                        </label>
                        <input
                            id="brand-name"
                            v-model="form.name"
                            type="text"
                            placeholder="Unesite naziv brenda..."
                            class="w-full rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition-colors placeholder:text-gray-400 focus:border-gray-900"
                            :class="{ 'border-red-300 focus:border-red-500': form.errors.name }"
                            autofocus
                        />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="brand-logo" class="mb-2 block text-sm font-medium text-gray-700">
                            Logo brenda <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="brand-logo"
                                type="file"
                                accept=".svg"
                                class="sr-only"
                                @change="onLogoChange"
                            />
                            <label
                                for="brand-logo"
                                class="flex cursor-pointer items-center gap-3 rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-600 transition-colors hover:border-gray-900 hover:text-gray-900"
                            >
                                <Icon name="tag" :size="16" />
                                <span class="flex-1 truncate">
                                    {{ logoFileName || 'Izaberite SVG fajl...' }}
                                </span>
                            </label>
                        </div>
                        <p v-if="form.errors.logo" class="mt-1.5 text-xs text-red-600">
                            {{ form.errors.logo }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            Podržan format: SVG, max 2MB
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                            @click="showAddModal = false"
                        >
                            Odustani
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-800 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Čuvanje...' : 'Sačuvaj' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>
