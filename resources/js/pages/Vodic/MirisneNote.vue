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
            name: 'Koje su glavne vrste mirisnih nota?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Glavne vrste mirisnih nota su: citrusne (limun, bergamot, narandža), cvetne (ruža, jasmin, lavanda), drvene (sandalovina, kedar, pačuli), orijentalne (vanila, amber, začini), fougère (mahovina, lavanda, kumarin), i kožne (duvan, koža, dim).',
            },
        },
        {
            '@type': 'Question',
            name: 'Šta znače citrusne note u parfemima?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Citrusne note su sveže i energične. Dolaze iz kora limuna, narandže, bergamota, grejpfruta i mandarine. Najčešće se koriste kao gornje note jer isparavaju brže od ostalih sastojaka. Idealne su za letnje i dnevne mirise.',
            },
        },
    ],
})
</script>

<template>
    <Head title="Vodič kroz mirisne note — dndparfems">
        <meta name="description" content="Sve o mirisnim notama: citrusne, drvene, orijentalne i cvetne. Nauči kako da prepoznaš i odabereš prave mirisne note za svoj parfem." />
    </Head>

    <component :is="'script'" type="application/ld+json" v-text="faqSchema" />

    <PageContainer max-width="narrow">
        <p class="mb-2 text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
            Vodič
        </p>
        <h1 class="font-serif text-4xl font-medium leading-[1.15] tracking-wide text-gray-900 lg:text-5xl">
            Vodič kroz mirisne note
        </h1>
        <p class="mt-3 text-sm text-gray-400">Citrusni, drveni, orijentalni, cvetni — Poslednji put ažurirano: 19. maj 2026.</p>

        <div class="mt-10 space-y-8 text-sm leading-relaxed text-gray-600">
            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Šta su mirisne note?</h2>
                <p>
                    Mirisne note su osnovni sastojci koji čine jedan parfem. Svaki parfem je harmonija različitih nota koje se slažu u tri nivoa — gornje, srednje i bazne note. Poznavanje mirisnih nota pomaže ti da lakše prepoznaš šta voliš i da odabereš parfem koji ti zaista odgovara.
                </p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Citrusne note — Svežina i energija</h2>
                <p>
                    Citrusne note su najlakše i najsvežije. Potiču iz kora citrusnog voća i karakteriše ih visok, blistav i energičan miris. Koriste se kao gornje note u većini parfema.
                </p>
                <div class="mt-3 rounded border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Primeri</p>
                    <p class="mt-1">Bergamot, limun, narandža, grejpfrut, mandarina, neroli, petitgrain</p>
                </div>
                <p class="mt-3">Najpoznatiji parfemi s citrusnim notama: Acqua di Gio (Armani), Light Blue (D&G), Chanel Chance Eau Fraîche.</p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Cvetne note — Romantika i elegancija</h2>
                <p>
                    Cvetne note su srce mnogih parfema. One su najraznovrsnija kategorija — od svežih i prozračnih do teških i opojnih mirisa.
                </p>
                <div class="mt-3 rounded border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Primeri</p>
                    <p class="mt-1">Ruža, jasmin, lavanda, ylang-ylang, ljiljan, violeta, iris, gardenija, magnolija</p>
                </div>
                <p class="mt-3">Najpoznatiji cvetni parfemi: Miss Dior (Dior), Coco Mademoiselle (Chanel), J'adore (Dior), Black Opium (YSL).</p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Drvene note — Snaga i trajnost</h2>
                <p>
                    Drvene note su tople, zemljane i postojane. One su najčešće bazne note koje parfemu daju dubinu i trajnost. Često se kombinuju sa citrusnim ili cvetnim notama za balans.
                </p>
                <div class="mt-3 rounded border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Primeri</p>
                    <p class="mt-1">Sandalovina, kedar, pačuli, vetiver, oud (agarovo drvo), gvajak drvo, mošus</p>
                </div>
                <p class="mt-3">Najpoznatiji drveni parfemi: Sauvage (Dior), Terre d'Hermès, Bleu de Chanel, Creed Aventus.</p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Orijentalne note — Toplina i senzualnost</h2>
                <p>
                    Orijentalne note su tople, slatke i začinske. One su najintenzivnija kategorija mirisa i najbolje se razvijaju na hladnom vremenu. Idealne su za večernje nošenje i zimske dane.
                </p>
                <div class="mt-3 rounded border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">Primeri</p>
                    <p class="mt-1">Vanila, amber, cimet, karanfilić, mošus, benzoin, tamjan, labdanum</p>
                </div>
                <p class="mt-3">Najpoznatiji orijentalni parfemi: La Vie Est Belle (Lancôme), Si (Armani), Libre (YSL), Black Opium (YSL).</p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Kako prepoznati note koje voliš?</h2>
                <ol class="space-y-3 list-decimal pl-5">
                    <li><strong class="text-gray-900">Zapamti parfeme koje si do sada nosio/la</strong> — potraži njihove note na kutiji ili online.</li>
                    <li><strong class="text-gray-900">Obrati pažnju na sastojke</strong> koje voliš u svakodnevnom životu (svežina limuna, toplina vanile).</li>
                    <li><strong class="text-gray-900">Testiraj jednu po jednu notu</strong> kroz jednostavne parfeme sa dominantnom notom.</li>
                    <li><strong class="text-gray-900">Zabeleži šta ti se dopada</strong> — vodi dnevnik mirisa da bi prepoznao/la obrasce.</li>
                </ol>
            </section>

            <section class="border-t border-gray-100 pt-8">
                <h2 class="mb-3 text-base font-semibold text-gray-900">Istraži mirise u našoj kolekciji</h2>
                <p>
                    Svaki parfem u našoj ponudi dolazi sa detaljnim opisom mirisnih nota. 
                    <Link :href="products.index.url()" class="font-medium text-gray-900 underline underline-offset-4 hover:text-gray-600">Pogledaj kolekciju</Link>
                    i pronađi note koje odgovaraju tvom ukusu.
                </p>
            </section>
        </div>
    </PageContainer>
</template>
