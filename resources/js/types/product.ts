export type ProductListItem = {
    id: number;
    slug: string;
    name: string;
    brand: string;
    image: string;
    price: string;
    sale_price: string;
    size_label: string;
    badge?: string;
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
        price: string;
        sale_price: string;
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
    price: string;
    sale_price: string;
    size_label: string;
    badge: string | null;
};
