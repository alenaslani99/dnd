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
            name: 'Kako odabrati parfem za početnike?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Počni tako što ćeš odrediti da li voliš sveže, citrusne, drvene ili orijentalne mirise. Testiraj parfem na koži, ne na papiriću. Nanesi ga na unutrašnju stranu ručnog zgloba i sačekaj 15-20 minuta da se razviju sve note. Najbolje vreme za testiranje je prepodne kada je čulo mirisa najosetljivije.',
            },
        },
        {
            '@type': 'Question',
            name: 'Koje su glavne vrste parfema?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Glavne vrste su: Parfem ekstrakt (20-40% mirisnog ulja, traje 8+ sati), EDP (15-20%, traje 6-8 sati), EDT (5-15%, traje 3-5 sati) i Kolonjska voda (2-5%, traje do 2 sata).',
            },
        },
        {
            '@type': 'Question',
            name: 'Gde kupiti kvalitetan parfem u Srbiji?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Kvalitetne i originalne parfeme u Srbiji možeš kupiti na dndparfems.rs. Nudimo širok izbor muških, ženskih i uniseks parfema svetskih brendova uz brzu dostavu širom Srbije.',
            },
        },
    ],
})
</script>

<template>
    <Head title="Kako odabrati parfem — Vodič za početnike — dndparfems">
        <meta name="description" content="Detaljan vodič za odabir parfema. Saznaj kako da prepoznaš mirisne note, razliku između EDT i EDP, i kako da testiraš parfem pre kupovine." />
    </Head>

    <component :is="'script'" type="application/ld+json" v-text="faqSchema" />

    <PageContainer max-width="narrow">
        <p class="mb-2 text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
            Vodič
        </p>
        <h1 class="font-serif text-4xl font-medium leading-[1.15] tracking-wide text-gray-900 lg:text-5xl">
            Kako odabrati parfem
        </h1>
        <p class="mt-3 text-sm text-gray-400">Vodič za početnike — Poslednji put ažurirano: 19. maj 2026.</p>

        <div class="mt-10 space-y-8 text-sm leading-relaxed text-gray-600">
            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Šta je parfem i kako nastaje?</h2>
                <p>
                    Parfem je mešavina mirisnih ulja, alkohola i vode. Mirisna ulja dobijaju se destilacijom, ekstrakcijom ili hladnim ceđenjem prirodnih sastojaka poput cveća, voća, začina i drveta. Kvalitet parfema zavisi od koncentracije mirisnog ulja — što je veća koncentracija, miris je intenzivniji i dugotrajniji.
                </p>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Tri nivoa mirisa — Piramida parfema</h2>
                <p>Svaki parfem se sastoji od tri nivoa mirisnih nota koje se razvijaju tokom vremena:</p>
                <ol class="mt-3 space-y-3 list-decimal pl-5">
                    <li><strong class="text-gray-900">Gornje note (head notes)</strong> — Prvi utisak, traje 15-30 minuta. Sastoji se od lakih, svežih mirisa poput citrusa, voća i začina.</li>
                    <li><strong class="text-gray-900">Srednje note (heart notes)</strong> — Srce parfema, traje 3-5 sati. Čine ga cvetni, voćni ili začinski mirisi.</li>
                    <li><strong class="text-gray-900">Bazne note (base notes)</strong> — Ono što ostaje na koži na kraju dana. Drvo, mošus, vanila, amber — ove note traju 6+ sati.</li>
                </ol>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Korak po korak: Kako testirati parfem</h2>
                <ol class="space-y-4">
                    <li>
                        <strong class="text-gray-900">1. Nemoj testirati više od 3 parfema odjednom.</strong>
                        <p>Tvoje čulo mirisa se brzo zasiti. Testiraj najviše 3 parfema u jednoj sesiji.</p>
                    </li>
                    <li>
                        <strong class="text-gray-900">2. Koristi bloter papirić za prvi utisak.</strong>
                        <p>Poprskaj parfem na papirić da osetiš gornje note. Ako ti se dopada, testiraj na koži.</p>
                    </li>
                    <li>
                        <strong class="text-gray-900">3. Nanesi na kožu i sačekaj.</strong>
                        <p>Poprskaj na unutrašnju stranu ručnog zgloba. Sačekaj 20 minuta da alkohol ispari i da se razviju srednje note.</p>
                    </li>
                    <li>
                        <strong class="text-gray-900">4. Oceni na kraju dana.</strong>
                        <p>Najbolji test je nošenje parfema tokom celog dana. Bazne note koje osetiš na kraju dana su one koje ćeš nositi.</p>
                    </li>
                </ol>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Kako odabrati parfem prema prilici</h2>
                <div class="mt-3 space-y-4">
                    <p><strong class="text-gray-900">Za svakodnevno nošenje (posao, škola):</strong> Laganiji mirisi poput citrusnih, vodenih ili svetlih cvetnih nota. EDT formati su idealni.</p>
                    <p><strong class="text-gray-900">Za večernje izlaske i specijalne prilike:</strong> Intenzivniji mirisi — orijentalni, drveni, gurmanski. EDP ili parfem ekstrakt su pravi izbor.</p>
                    <p><strong class="text-gray-900">Za letnje dane:</strong> Sveži, citrusni, morski mirisi koji neće biti zagušljivi na vrućini.</p>
                    <p><strong class="text-gray-900">Za zimske dane:</strong> Topli, začinski, drveni i gurmanski mirisi koji se bolje razvijaju na hladnoći.</p>
                </div>
            </section>

            <section>
                <h2 class="mb-3 text-base font-semibold text-gray-900">Najčešće greške pri odabiru parfema</h2>
                <ul class="space-y-2 list-disc pl-5">
                    <li>Kupovina samo na osnovu prvog utiska sa papirića</li>
                    <li>Trljanje parfema posle nanošenja (razbija mirisne molekule)</li>
                    <li>Nošenje pogrešnog mirisa za godišnje doba</li>
                    <li>Previše sprejeva — parfem treba da se oseti u zoni ličnog prostora, ne u celoj prostoriji</li>
                    <li>Kupovina na osnovu marke, a ne mirisa koji ti zaista prija</li>
                </ul>
            </section>

            <section class="border-t border-gray-100 pt-8">
                <h2 class="mb-3 text-base font-semibold text-gray-900">Istraži našu kolekciju</h2>
                <p>
                    Pogledaj našu pažljivo odabranu kolekciju
                    <Link :href="products.index.url()" class="font-medium text-gray-900 underline underline-offset-4 hover:text-gray-600">luksuznih parfema</Link>
                    i pronađi miris koji govori tvoju priču.
                </p>
            </section>
        </div>
    </PageContainer>
</template>
