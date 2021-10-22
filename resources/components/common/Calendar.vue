<template>
    <v-card style="width: 100%" class="rounded-xl overflow-hidden">
        <div class="d-flex justify-lg-space-between align-center datetimes">
            <v-btn
                icon
                class="ma-2 black--text"
                @click="$store.commit('decrementMonth')"
            >
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <p
                class="
                    font-weight-bold
                    ma-0
                    text-center
                    flex-grow-1
                    text-uppercase
                    black--text
                "
            >
                {{ currentYearMonth }}
            </p>
            <v-btn
                icon
                class="ma-2 black--text"
                @click="$store.commit('incrementMonth')"
            >
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
        </div>

        <v-calendar
            ref="calendar"
            v-if="isLogged"
            v-model="date"
            type="month"
            :events="user.happenings"
            style="height: auto"
            class="mb-4"
            @click:event="goToHappening"
        />
    </v-card>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
    name: "Calendar",
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            date: "formatedCalendarDate",
            currentYearMonth: "currentYearMonth",
        }),
    },
    methods: {
        goToHappening(happeningEvent) {
            if (happeningEvent.event.surveyId) {
                this.$router.push({
                    name: "SurveyPage",
                    params: { id: happeningEvent.event.surveyId },
                });
            } else {
                this.$router.push({
                    name: "HappeningPage",
                    params: { id: happeningEvent.event.id },
                });
            }
        },
    },
};
</script>
