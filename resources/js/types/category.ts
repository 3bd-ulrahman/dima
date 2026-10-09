export type Category = {
    id: number;
    name: string;
    slug: string;
    products_count: number;
    created_at: string;
    updated_at: string;
};

export type CategoryPayload = {
    name: string;
};

export type CategoryFilters = {
    per_page: number;
};
