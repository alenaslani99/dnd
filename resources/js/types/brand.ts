export type Brand = {
    id: number;
    name: string;
    slug: string;
    logo: string | null;
};

export type BrandWithCount = Brand & {
    products_count: number;
};
