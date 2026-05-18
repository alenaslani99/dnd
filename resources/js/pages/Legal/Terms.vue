<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PageContainer from '@/components/PageContainer.vue'

defineOptions({ layout: AppLayout })
</script>

<template>
    <Head title="Uslovi korišćenja — dndparfems">
        <meta name="description" content="Uslovi korišćenja dndparfems online prodavnice. Saznaj više o pravilima kupovine, isporuci i reklamacijama." />
    </Head>

    <PageContainer max-width="narrow">
        <h1 class="mb-4 font-serif text-4xl font-medium tracking-wide text-gray-900">
            Uslovi korišćenja
        </h1>
        <p class="mb-8 text-sm text-gray-500">Poslednja izmena: 19. maj 2026.</p>

        <div class="space-y-6 text-sm leading-relaxed text-gray-600">
            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">1. Opšti uslovi</h2>
                <p>
                    Korišćenjem sajta dndparfems.rs prihvataš sledeće uslove korišćenja. Zadržavamo pravo izmene uslova u bilo kom trenutku, o čemu ćeš biti blagovremeno obavešten.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">2. Cene i plaćanje</h2>
                <p>
                    Sve cene su izražene u dinarima (RSD) i uključuju PDV. Plaćanje se vrši pouzećem prilikom preuzimanja pošiljke. Zadržavamo pravo izmene cena bez prethodne najave.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">3. Isporuka</h2>
                <p>
                    Isporuka se vrši na teritoriji Republike Srbije putem kurirske službe. Vreme isporuke je 2-5 radnih dana. Troškove dostave snosi kupac, osim ako nije drugačije naglašeno.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">4. Reklamacije i povraćaj</h2>
                <p>
                    Imate pravo da u roku od 14 dana od dana prijema pošiljke izvršite povraćaj ili reklamaciju proizvoda. Proizvod mora biti neoštećen i u originalnom pakovanju. Za više informacija kontaktiraj nas na info@dndparfems.rs.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">5. Odgovornost</h2>
                <p>
                    dndparfems ne odgovara za štetu nastalu nepravilnim korišćenjem proizvoda. Svi proizvodi su originalni i podležu garanciji proizvođača.
                </p>
            </section>
        </div>
    </PageContainer>
</template>
