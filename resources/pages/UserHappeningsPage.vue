<template>
    <div>
        <div v-if="!isLogged" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
        </div>

        <div v-else>
            <v-card class="rounded-xl">
                <v-card-title class="default black--text">
                    Mes évènements
                </v-card-title>
                <v-card-text>
                    <v-checkbox
                        v-model="showOnlyUpcoming"
                        label="Uniquement les évènements à venir"
                    />
                    <v-data-table
                        :headers="headers"
                        :items="
                            showOnlyUpcoming
                                ? user.upcomingHappenings
                                : user.happenings
                        "
                        @click:row="goToHappening"
                    >
                        <template v-slot:[getItemStatus]="{ item }">
                            <v-chip dark :color="item.color">
                                {{ item.label }}
                            </v-chip>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
    name: "UserHappeningsPage",
    data: () => ({
        headers: [
            {
                text: "Nom",
                value: "name",
            },
            { text: "Groupe", value: "team.name" },
            { text: "Date", value: "startAt" },
            { text: "Status", value: "status" },
        ],
        showOnlyUpcoming: true,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            isUserAdmin: "team/isUserAdmin",
        }),
        getItemStatus() {
            return `item.status`;
        },
    },
    created() {
        this.$store.commit("setTitle", "Mes évènements");
        this.$store.commit("setColor", "default");
    },
    methods: {
        goToHappening(happening) {
            if (happening.surveyId) {
                this.$router.push({
                    name: "SurveyPage",
                    params: { id: happening.surveyId },
                });
            } else {
                this.$router.push({
                    name: "HappeningPage",
                    params: { id: happening.id },
                });
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.text-start {
    cursor: pointer;
}
</style>
