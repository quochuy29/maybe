import './bootstrap';
import {createApp} from 'vue';
import Router from './router/router';
import App from './App.vue';
import SoftUIDashboard from "./asset/soft-ui-dashboard";
import BootstrapVue3 from 'bootstrap-vue-3';

const app = createApp(App).use(Router).use(SoftUIDashboard).use(BootstrapVue3);

function importModuleComponents() {
    var context =  import.meta.globEager('../../Modules/**/*.vue');
    var component = import.meta.globEager('./component/**/*.vue');
    for (const key of Object.keys(context)) {
        const module = context[key].default;
        const name = key.split('/').pop().split('.')[0];
        app.component(name, module);
    }

    for (const key of Object.keys(component)) {
        const module = component[key].default;
        const name = key.split('/').pop().split('.')[0];
        app.component(name, module);
    }
}
importModuleComponents();  

app.mount("#app");