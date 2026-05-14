<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import FormField from '@/components/FormField.vue'
import PhoneInput from '@/components/PhoneInput.vue'
import { formatPrice } from '@/lib/utils'
import { PHONE_REGEX, HOUSE_NUMBER_REGEX, ZIP_REGEX } from '@/lib/validation'
import checkoutRoutes from '@/routes/checkout'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    items: {
        id: number
        quantity: number
        product_name: string
        brand_name: string
        size_label: string | null
        unit_price: number
        total_price: number
    }[]
    summary: {
        subtotal: number
        shipping: number
        total: number
    }
}>()

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string; phone: string } | null)

const form = useForm({
    name: authUser.value?.name ?? '',
    email: authUser.value?.email ?? '',
    phone: authUser.value?.phone ?? '',
    address: '',
    house_number: '',
    zip: '',
    city: '',
})

function validateAndSubmit() {
    form.clearErrors()

    let hasError = false

    if (! authUser.value) {
        if (! form.name.trim()) {
            form.setError('name', 'Ime i prezime su obavezni.')
            hasError = true
        }
        if (! form.email.trim()) {
            form.setError('email', 'Email adresa je obavezna.')
            hasError = true
        }
        if (! PHONE_REGEX.test(form.phone)) {
            form.setError('phone', 'Broj telefona mora biti u formatu +381 60 1234567.')
            hasError = true
        }
    }

    if (! form.address.trim()) {
        form.setError('address', 'Adresa je obavezna.')
        hasError = true
    }

    if (! HOUSE_NUMBER_REGEX.test(form.house_number)) {
        form.setError('house_number', 'Kućni broj može biti do 5 cifara ili "bb".')
        hasError = true
    }

    if (! ZIP_REGEX.test(form.zip)) {
        form.setError('zip', 'Poštanski broj mora imati tačno 5 cifara.')
        hasError = true
    }

    if (! form.city.trim()) {
        form.setError('city', 'Grad je obavezan.')
        hasError = true
    }

    if (hasError) return

    form.post(checkoutRoutes.store.url())
}
</script>

<template>
    <Head title="Checkout" />

    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
        <h1 class="mb-12 text-center font-serif text-4xl font-medium tracking-wide text-gray-900">
            Checkout
        </h1>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
            <!-- Shipping Form -->
            <form @submit.prevent="validateAndSubmit" class="space-y-8">
                <div v-if="!authUser">
                    <h2 class="mb-6 text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Lični podaci
                    </h2>
                    <div class="space-y-6">
                        <FormField label="Ime i prezime" :error="form.errors.name">
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                maxlength="255"
                                class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                        </FormField>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <FormField label="Email" :error="form.errors.email">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    maxlength="255"
                                    class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                            </FormField>
                            <FormField label="Telefon" :error="form.errors.phone">
                                <PhoneInput v-model="form.phone" />
                            </FormField>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-6 text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Adresa dostave
                    </h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div class="sm:col-span-2">
                                <FormField label="Ulica" :error="form.errors.address">
                                    <input
                                        v-model="form.address"
                                        type="text"
                                        required
                                        maxlength="255"
                                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                                        :class="{ 'border-red-500': form.errors.address }"
                                    />
                                </FormField>
                            </div>
                            <div>
                                <FormField label="Broj kuće" :error="form.errors.house_number">
                                    <input
                                        v-model="form.house_number"
                                        type="text"
                                        required
                                        maxlength="5"
                                        pattern="(\d{1,5}|bb|BB)"
                                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                                        :class="{ 'border-red-500': form.errors.house_number }"
                                    />
                                </FormField>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <FormField label="Poštanski broj" :error="form.errors.zip">
                                    <input
                                        v-model="form.zip"
                                        type="text"
                                        required
                                        maxlength="5"
                                        pattern="\d{5}"
                                        inputmode="numeric"
                                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                                        :class="{ 'border-red-500': form.errors.zip }"
                                    />
                                </FormField>
                            </div>
                            <div>
                                <FormField label="Grad" :error="form.errors.city">
                                    <input
                                        v-model="form.city"
                                        type="text"
                                        required
                                        maxlength="100"
                                        class="w-full border-b border-gray-300 bg-transparent px-1 py-3 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                                        :class="{ 'border-red-500': form.errors.city }"
                                    />
                                </FormField>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-6 text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Način plaćanja
                    </h2>
                    <div class="border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="text-sm font-medium text-gray-900">Pouzećem</p>
                        <p class="text-xs text-gray-500 mt-1">Plaćanje prilikom preuzimanja pošiljke.</p>
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full border border-gray-900 bg-gray-900 px-8 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50"
                >
                    {{ form.processing ? 'Obrada...' : 'Poruči' }}
                </button>
            </form>

            <!-- Order Summary -->
            <div class="border border-gray-100 bg-gray-50 p-8 h-fit">
                <h2 class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                    Porudžbina
                </h2>

                <div class="mt-6 space-y-6">
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="flex justify-between text-sm"
                    >
                        <div>
                            <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                            <p class="text-gray-500">{{ item.brand_name }} — {{ item.size_label }} × {{ item.quantity }}</p>
                        </div>
                        <p class="font-medium text-gray-900">{{ formatPrice(item.total_price) }}</p>
                    </div>
                </div>

                <div class="mt-8 space-y-3 border-t border-gray-200 pt-6">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Međuzbir</span>
                        <span>{{ formatPrice(summary.subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Dostava</span>
                        <span>{{ formatPrice(summary.shipping) }}</span>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                        <span>Ukupno</span>
                        <span>{{ formatPrice(summary.total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
