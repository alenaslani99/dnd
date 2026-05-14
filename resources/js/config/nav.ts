import type { InertiaLinkProps } from '@inertiajs/vue3'
import products from '@/routes/products'
import brands from '@/routes/brands'

export interface DropdownColumn {
    title: string
    href: string
    brands: { name: string; href: string }[]
}

export interface NavLink {
    label: string
    href: InertiaLinkProps['href']
    dropdown?: DropdownColumn[]
}

export const navLinks: NavLink[] = [
    { label: 'Početna', href: '/' },
    {
        label: 'Parfemi',
        href: products.index.url(),
        dropdown: [
            {
                title: 'Muški parfemi',
                href: products.index.url({ query: { genders: ['male'] } }),
                brands: [
                    { name: 'Chanel', href: products.index.url({ query: { genders: ['male'], brands: ['chanel'] } }) },
                    { name: 'Dior', href: products.index.url({ query: { genders: ['male'], brands: ['dior'] } }) },
                    { name: 'Versace', href: products.index.url({ query: { genders: ['male'], brands: ['versace'] } }) },
                    { name: 'Giorgio Armani', href: products.index.url({ query: { genders: ['male'], brands: ['giorgio-armani'] } }) },
                    { name: 'Yves Saint Laurent', href: products.index.url({ query: { genders: ['male'], brands: ['yves-saint-laurent'] } }) },
                ],
            },
            {
                title: 'Ženski parfemi',
                href: products.index.url({ query: { genders: ['female'] } }),
                brands: [
                    { name: 'Chanel', href: products.index.url({ query: { genders: ['female'], brands: ['chanel'] } }) },
                    { name: 'Dior', href: products.index.url({ query: { genders: ['female'], brands: ['dior'] } }) },
                    { name: 'Versace', href: products.index.url({ query: { genders: ['female'], brands: ['versace'] } }) },
                    { name: 'Dolce & Gabbana', href: products.index.url({ query: { genders: ['female'], brands: ['dolce-gabbana'] } }) },
                    { name: 'Mancera', href: products.index.url({ query: { genders: ['female'], brands: ['mancera'] } }) },
                ],
            },
            {
                title: 'Uniseks / Pokloni',
                href: products.index.url({ query: { genders: ['unisex'] } }),
                brands: [
                    { name: 'Creed', href: products.index.url({ query: { genders: ['unisex'], brands: ['creed'] } }) },
                    { name: 'Maison Francis Kurkdjian', href: products.index.url({ query: { genders: ['unisex'], brands: ['maison-francis-kurkdjian'] } }) },
                    { name: 'Byredo', href: products.index.url({ query: { genders: ['unisex'], brands: ['byredo'] } }) },
                    { name: 'Diptyque', href: products.index.url({ query: { genders: ['unisex'], brands: ['diptyque'] } }) },
                    { name: 'Tom Ford', href: products.index.url({ query: { genders: ['unisex'], brands: ['tom-ford'] } }) },
                ],
            },
        ],
    },
    { label: 'Brendovi', href: brands.index.url() },
    { label: 'Kontakt', href: '#' },
]
