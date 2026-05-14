export type CartItem = {
    id: number;
    quantity: number;
    variant: { id: number; size_label: string | null; sku: string };
    product: { slug: string; name: string; brand: string; image: string };
    unit_price: number | null;
    total_price: number | null;
};

export type Cart = {
    items: CartItem[];
    total: number | null;
    shipping_cost: number;
};
