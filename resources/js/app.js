import './bootstrap';
import {createApp} from 'vue';
import Router from './router/router';
import App from './App.vue';
import SoftUIDashboard from "./asset/soft-ui-dashboard";
import Card from './component/Cards/Card.vue';

const app = createApp(App).use(Router).use(SoftUIDashboard);

function importModuleComponents() {
    var context =  import.meta.globEager('../../Modules/**/*.vue');
    var card = import.meta.globEager('./component/Cards/*.vue');
    for (const key of Object.keys(context)) {
        const module = context[key].default;
        const name = key.split('/').pop().split('.')[0];
        app.component(name, module);
    }

    for (const key of Object.keys(card)) {
        const module = card[key].default;
        const name = key.split('/').pop().split('.')[0];
        app.component(name, module);
    }
}
importModuleComponents();  

app.mount("#app");