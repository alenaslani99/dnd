export type ProductListItem = {
    id: number;
    slug: string;
    name: string;
    brand: string;
    image: string;
    price: number | null;
    sale_price: number | null;
    size_label: string | null;
    badge?: string | null;
};

export type ProductDetail = {
    id: number;
    slug: string;
    name: string;
    description: string;
    brand: string;
    images: { path: string; alt: string | null; is_primary: boolean }[];
    variants: {
        id: number;
        size_label: string | null;
        sku: string;
        price: number | null;
        sale_price: number | null;
        is_available: boolean;
    }[];
};

export type ProductFilters = {
    brands: string[];
    sizes: string[];
    genders: string[];
    sort: string;
};

export type FeaturedProduct = {
    slug: string;
    name: string;
    brand: string;
    image: string;
    price: number | null;
    sale_price: number | null;
    size_label: string | null;
    badge: string | null;
};
