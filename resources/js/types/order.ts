export type OrderItem = {
    product_name: string;
    brand_name: string;
    size_label: string | null;
    quantity: number;
    unit_price: number;
    total_price: number;
};

export type Order = {
    order_number: string;
    status: string;
    total_amount: number;
    shipping_cost: number;
    created_at: string;
    items: OrderItem[];
};
