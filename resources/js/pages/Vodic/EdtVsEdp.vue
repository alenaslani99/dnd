<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PageContainer from '@/components/PageContainer.vue'
import products from '@/routes/products'

defineOptions({ layout: AppLayout })

const faqSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: [
        {
            '@type': 'Question',
            name: 'Koja je razlika između EDT i EDP parfema?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'EDT (Eau de Toilette) ima 5-15% mirisnog ulja i traje 3-5 sati. EDP (Eau de Parfum) ima 15-20% mirisnog ulja i traje 6-8 sati. EDP ima intenzivniji miris, veću cenu i dužu postojanost. EDT je laganiji, svežiji i pristupačniji.',
            },
        },
        {
            '@type': 'Question',
            name: 'Šta je parfem ekstrakt?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Parfem ekstrakt (Extrait de Parfum) je najkoncentrovanija forma mirisa sa 20-40% mirisnog ulja. Ima najdužu postojanost (8+ sati) i najskuplji je. Dovoljna je jedna kap za ceo dan. Najčešće se prodaje u manjim pakovanjima od 7.5ml do 15ml.',
            },
        },
        {
            '@type': 'Question',
            name: 'Šta je bolje — EDT ili EDP?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Nijedan nije bolji — zavisi od tvojih potreba. EDT je bolji za svakodnevno nošenje, letnje dane i ako voliš laganije mirise. EDP je bolji za večernje izlaske, zimske dane i kada želiš da miris traje ceo dan. Najbolje je imati oba formata u svojoj kolekciji.',
            },
        },
    ],
})
</script>

<template>
    <Head title="EDT vs EDP — Razlike i vodič — dndparfems">
        <meta name="description" content="Saznaj koja je razlika između EDT i EDP parfema. Poređenje koncentracije, trajanja, cene i preporuke za korišćenje. Detaljan vodič za odabir pravog formata." />
    </Head>

    <component :is="'script'" type="application/ld+json" v-text="faqSchema" />

    <PageContainer max-width="narrow">
        <p class="mb-2 text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
            Vodič
        </p>
        <h1 class="font-serif text-4xl font-medium leading-[1.15] tracking-wide text-gray-900 lg:text-5xl">
            EDT vs EDP — Koja je razlika?
        </h1>
        <p class="mt-3 text-sm text-gray-400">Kompletan vodič kroz vrste parfema — Poslednji put ažurirano: 19. maj 2026.</p>

        <div class="mt-10 space-y-8 text-sm leading-relaxed text-gray-600">
            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Šta znače EDT, EDP i druge oznake?</h2>
                <p>
                    Oznake na kutiji parfema (EDT, EDP, Parfem ekstrakt) označavaju koncentraciju mirisnog ulja u mešavini. Što je veća koncentracija, miris je intenzivniji, duže traje i skuplji je. Ova terminologija potiče iz francuske parfimerije i koristi se širom sveta.
                </p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Poređenje: EDT vs EDP vs Parfem ekstrakt</h2>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-left">
                                <th class="py-3 pr-4 font-semibold text-gray-900">Tip</th>
                                <th class="py-3 pr-4 font-semibold text-gray-900">Koncentracija</th>
                                <th class="py-3 pr-4 font-semibold text-gray-900">Trajanje</th>
                                <th class="py-3 font-semibold text-gray-900">Cena</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-900">Parfem ekstrakt</td>
                                <td class="py-3 pr-4">20-40%</td>
                                <td class="py-3 pr-4">8+ sati</td>
                                <td class="py-3">Najskuplji</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-900">EDP (Eau de Parfum)</td>
                                <td class="py-3 pr-4">15-20%</td>
                                <td class="py-3 pr-4">6-8 sati</td>
                                <td class="py-3">Srednja</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-900">EDT (Eau de Toilette)</td>
                                <td class="py-3 pr-4">5-15%</td>
                                <td class="py-3 pr-4">3-5 sati</td>
                                <td class="py-3">Pristupačan</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-900">Kolonjska voda (Eau de Cologne)</td>
                                <td class="py-3 pr-4">2-5%</td>
                                <td class="py-3 pr-4">Do 2 sata</td>
                                <td class="py-3">Najpovoljniji</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Kada odabrati EDT?</h2>
                <p>EDT je idealan izbor u sledećim situacijama:</p>
                <ul class="mt-3 space-y-2 list-disc pl-5">
                    <li>Za svakodnevno nošenje na posao ili fakultet</li>
                    <li>Tokom letnjih meseci kada su visoke temperature</li>
                    <li>Ako voliš da menjaš parfem više puta dnevno</li>
                    <li>Kada probaš novi miris i nisi siguran/na da li ti odgovara</li>
                    <li>Za sportske aktivnosti i neformalne prilike</li>
                </ul>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Kada odabrati EDP?</h2>
                <p>EDP je bolji izbor kada:</p>
                <ul class="mt-3 space-y-2 list-disc pl-5">
                    <li>Želiš da miris traje ceo dan bez ponovnog nanošenja</li>
                    <li>Izlaziš uveče ili imaš svečanu priliku</li>
                    <li>Tokom zimskih meseci — hladnoća bolje čuva miris</li>
                    <li>Želiš intenzivniji mirisni doživljaj</li>
                    <li>Imaš omiljeni parfem koji želiš u jačoj verziji</li>
                </ul>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Da li EDT i EDP istog parfema mirišu isto?</h2>
                <p>
                    Ne uvek. Iako su napravljeni od istih mirisnih nota, zbog različite koncentracije alkohola i ulja, EDT i EDP mogu da mirišu drugačije. EDT je obično svežiji i lakši jer alkohol brže isparava i nosi gornje note. EDP je dublji i bogatiji jer više mirisnog ulja omogućava bolji razvoj srednjih i baznih nota. Zato je uvek preporuka da testiraš oba formata pre kupovine.
                </p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Kako pravilno naneti parfem?</h2>
                <ol class="space-y-2 list-decimal pl-5">
                    <li>Nanesi na tople tačke tela: ručni zglobovi, iza ušiju, vrat, iza kolena</li>
                    <li>Drži bočicu 15-20 cm od kože prilikom sprejanja</li>
                    <li>Ne trljaj parfem posle nanošenja — to razbija mirisne molekule</li>
                    <li>Nanesi na hidratizovanu kožu za bolju postojanost</li>
                    <li>Ne preteruj — dva do tri spreja su dovoljna</li>
                </ol>
            </section>

            <section class="border-t border-gray-100 pt-8">
                <h2 class="mb-3 text-base font-semibold text-gray-900">Istraži EDT i EDP parfeme u našoj ponudi</h2>
                <p>
                    U našoj kolekciji <Link :href="products.index.url()" class="font-medium text-gray-900 underline underline-offset-4 hover:text-gray-600">luksuznih parfema</Link>
                    pronađi svoj omiljeni miris u formatu koji ti odgovara — bilo da je EDT za svaki dan ili EDP za posebne trenutke.
                </p>
            </section>
        </div>
    </PageContainer>
</template>
