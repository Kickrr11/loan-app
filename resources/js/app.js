import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Highcharts from 'highcharts';
import HighchartsVue from 'highcharts-vue';
import exportingInit from 'highcharts/modules/exporting';
import exportData from "highcharts/modules/export-data";

exportingInit(Highcharts)
exportData(Highcharts);


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(HighchartsVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
