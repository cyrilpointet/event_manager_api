import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import "roboto-fontface/css/roboto/roboto-fontface.css";
import "@mdi/font/css/materialdesignicons.css";
import dayjs from "dayjs";
import * as utc from "dayjs/plugin/utc";
import * as locale from "dayjs/locale/fr";
import "./assets/app.scss";

dayjs.extend(utc);
dayjs.locale(locale);

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    vuetify,
    created() {
        window.addEventListener("beforeinstallprompt", (e) => {
            e.preventDefault();
            if (window.matchMedia("(orientation: portrait)").matches) {
                this.$store.commit("setDeferredPrompt", e);
            }
        });
    },
    render: (h) => h(App),
}).$mount("#app");
