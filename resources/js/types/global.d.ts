import { route as routeFn } from 'ziggy-js';
import type { Auth } from '@/types/auth';

export {};

declare global {
    const route: typeof routeFn;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof routeFn;
    }
}

declare module '@inertiajs/core' {
    interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            cartCount: number;
            flash: {
                success?: string;
                error?: string;
            };
        };
    }
}
