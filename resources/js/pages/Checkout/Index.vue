<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import OrderSummary from '@/components/OrderSummary.vue'
import PageContainer from '@/components/PageContainer.vue'
import PhoneInput from '@/components/PhoneInput.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
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
        total: number
    }
}>()

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string; phone?: string } | null)

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

    <PageContainer>
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
                        <BaseInput
                            v-model="form.name"
                            label="Ime i prezime"
                            required
                            maxlength="255"
                            :error="form.errors.name"
                        />
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <BaseInput
                                v-model="form.email"
                                label="Email"
                                type="email"
                                required
                                maxlength="255"
                                :error="form.errors.email"
                            />
                            <div>
                                <label class="mb-2 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                                    Telefon
                                </label>
                                <PhoneInput v-model="form.phone" />
                                <p v-if="form.errors.phone" class="mt-2 text-xs text-red-500">{{ form.errors.phone }}</p>
                            </div>
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
                                <BaseInput
                                    v-model="form.address"
                                    label="Ulica"
                                    required
                                    maxlength="255"
                                    :error="form.errors.address"
                                />
                            </div>
                            <BaseInput
                                v-model="form.house_number"
                                label="Broj kuće"
                                required
                                maxlength="5"
                                pattern="(\d{1,5}|bb|BB)"
                                :error="form.errors.house_number"
                            />
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <BaseInput
                                v-model="form.zip"
                                label="Poštanski broj"
                                required
                                maxlength="5"
                                pattern="\d{5}"
                                inputmode="numeric"
                                :error="form.errors.zip"
                            />
                            <BaseInput
                                v-model="form.city"
                                label="Grad"
                                required
                                maxlength="100"
                                :error="form.errors.city"
                            />
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

                <PrimaryButton :loading="form.processing" loading-text="Obrada...">
                    Poruči
                </PrimaryButton>
            </form>

            <!-- Order Summary -->
            <OrderSummary
                :items="items"
                :subtotal="summary.subtotal"
                :shipping="500"
                :total="summary.total"
            />
        </div>
    </PageContainer>
</template>
