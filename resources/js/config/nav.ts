import type { InertiaLinkProps } from '@inertiajs/vue3'

export interface NavLink {
    label: string
    href: InertiaLinkProps['href']
}

export const navLinks: NavLink[] = [
    { label: 'Početna', href: '/' },
    { label: 'Parfemi', href: '#' },
    { label: 'Brendovi', href: '#' },
    { label: 'Kontakt', href: '#' },
]
