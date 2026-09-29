import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { route as routeFn } from 'ziggy-js';
import AppLayout from '@/Layouts/AppLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Dima';

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof routeFn;
    }
}

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    progress: {
        color: '#059669',
    },
    resolve: (name) => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true });
        return pages[`./pages/${name}.vue`] as any;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.config.globalProperties.route = routeFn;

        app.mount(el);
    },
});
