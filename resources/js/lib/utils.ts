import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatPrice(price: number | null): string {
    if (!price) return '';
    return new Intl.NumberFormat('sr-RS').format(price) + ' RSD';
}

export function statusLabel(status: string): string {
    const map: Record<string, string> = {
        pending: 'Na čekanju',
        processing: 'U obradi',
        shipped: 'Poslato',
        delivered: 'Dostavljeno',
        cancelled: 'Otkazano',
        refunded: 'Refundirano',
    };

    return map[status] ?? status;
}
