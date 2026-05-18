<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PageContainer from '@/components/PageContainer.vue'

defineOptions({ layout: AppLayout })
</script>

<template>
    <Head title="Privatnost — dndparfems">
        <meta name="description" content="Politika privatnosti dndparfems. Saznaj kako čuvamo i koristimo tvoje lične podatke." />
    </Head>

    <PageContainer max-width="narrow">
        <h1 class="mb-4 font-serif text-4xl font-medium tracking-wide text-gray-900">
            Politika privatnosti
        </h1>
        <p class="mb-8 text-sm text-gray-500">Poslednja izmena: 19. maj 2026.</p>

        <div class="space-y-6 text-sm leading-relaxed text-gray-600">
            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">1. Prikupljanje podataka</h2>
                <p>
                    Prilikom korišćenja naše prodavnice prikupljamo sledeće podatke: ime i prezime, email adresu, broj telefona, adresu dostave i podatke o porudžbinama. Ove podatke koristimo isključivo za obradu i isporuku porudžbina.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">2. Korišćenje podataka</h2>
                <p>
                    Tvoje podatke koristimo za: obradu porudžbina, komunikaciju u vezi sa porudžbinom, slanje informativnih emailova (uz tvoju saglasnost) i unapređenje naših usluga.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">3. Čuvanje podataka</h2>
                <p>
                    Tvoje podatke čuvamo onoliko dugo koliko je potrebno za pružanje usluga i ispunjavanje zakonskih obaveza. Podatke o porudžbinama čuvamo u skladu sa važećim propisima Republike Srbije.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">4. Prava korisnika</h2>
                <p>
                    U svakom trenutku imaš pravo da: zatražiš uvid u svoje podatke, zatražiš izmenu ili brisanje podataka, povučeš saglasnost za obradu podataka, podneseš prigovor nadležnom organu.
                </p>
            </section>

            <section>
                <h2 class="mb-2 text-base font-semibold text-gray-900">5. Kontakt</h2>
                <p>
                    Za sva pitanja u vezi sa privatnošću možeš nas kontaktirati na email: info@dndparfems.rs.
                </p>
            </section>
        </div>
    </PageContainer>
</template>
