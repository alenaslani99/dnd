import type { InertiaLinkProps } from '@inertiajs/vue3'

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
        href: '/parfemi',
        dropdown: [
            {
                title: 'Muški parfemi',
                href: '#',
                brands: [
                    { name: 'Chanel', href: '#' },
                    { name: 'Dior', href: '#' },
                    { name: 'Versace', href: '#' },
                    { name: 'Giorgio Armani', href: '#' },
                    { name: 'Yves Saint Laurent', href: '#' },
                ],
            },
            {
                title: 'Ženski parfemi',
                href: '#',
                brands: [
                    { name: 'Chanel', href: '#' },
                    { name: 'Dior', href: '#' },
                    { name: 'Versace', href: '#' },
                    { name: 'Dolce & Gabbana', href: '#' },
                    { name: 'Mancera', href: '#' },
                ],
            },
            {
                title: 'Uniseks / Pokloni',
                href: '#',
                brands: [
                    { name: 'Creed', href: '#' },
                    { name: 'Maison Francis Kurkdjian', href: '#' },
                    { name: 'Byredo', href: '#' },
                    { name: 'Diptyque', href: '#' },
                    { name: 'Tom Ford', href: '#' },
                ],
            },
        ],
    },
    { label: 'Brendovi', href: '/brendovi' },
    { label: 'Kontakt', href: '#' },
]
