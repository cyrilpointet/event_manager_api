import Vue from "vue";
import Vuetify from "vuetify";
import fr from "vuetify/lib/locale/fr";
import colors from "vuetify/lib/util/colors";
import "vuetify/dist/vuetify.min.css";

Vue.use(Vuetify);

const opts = {
    lang: {
        locales: { fr },
        current: "fr",
    },
    theme: {
        themes: {
            light: {
                primary: colors.cyan.darken3,
                secondary: colors.lightGreen.darken3,
                accent: colors.orange.darken3,
                warning: colors.orange,
                error: colors.red.darken2,
                black: colors.black,
                default: colors.cyan,
                survey: "#de9585",
                events: "#d4bfff",
                team: "#fc8b65",
                user: "#ffad66",
                datetimes: "#7eb1fc",
            },
        },
        options: {
            customProperties: true,
        },
    },
};

export default new Vuetify(opts);
