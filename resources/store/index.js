import Vue from "vue";
import Vuex from "vuex";
import { userStore } from "./userStore";
import { teamStore } from "./teamStore";
import { happeningStore } from "./happeningStore";
import { surveyStore } from "./surveyStore";
import { formStore } from "./formStore";
import dayjs from "dayjs";
import * as locale from "dayjs/locale/fr";
dayjs.locale(locale);

Vue.use(Vuex);

export default new Vuex.Store({
    state: () => ({
        title: "Home",
        color: "primary",
        calendarDate: dayjs(),
        deferredPrompt: null,
    }),
    getters: {
        formatedCalendarDate(state) {
            return state.calendarDate.format("YYYY-MM-DD");
        },
        currentYearMonth(state) {
            return state.calendarDate.format("MMMM - YYYY");
        },
    },
    mutations: {
        setDeferredPrompt(state, value) {
            state.deferredPrompt = value;
        },
        setTitle(state, value) {
            state.title = value;
        },
        setColor(state, value) {
            state.color = value;
        },
        incrementMonth(state) {
            state.calendarDate = state.calendarDate.add(1, "M");
        },
        decrementMonth(state) {
            state.calendarDate = state.calendarDate.subtract(1, "M");
        },
    },
    modules: {
        user: userStore,
        team: teamStore,
        happening: happeningStore,
        survey: surveyStore,
        form: formStore,
    },
});
