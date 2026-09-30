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
    search: string;
    sort: string;
    per_page: number;
};
