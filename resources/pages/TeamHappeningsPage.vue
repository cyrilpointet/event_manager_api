<template>
    <div>
        <div v-if="!isLogged || !team" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
        </div>

        <div v-else>
            <v-card class="rounded-xl">
                <v-card-title class="default black--text">
                    {{ team.name }}
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
                                ? user.getUpcommingHappeningsByTeam(team.id)
                                : user.getHappeningsByTeam(team.id)
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
            { text: "Date", value: "startAt" },
            { text: "Status", value: "status" },
        ],
        showOnlyUpcoming: true,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            team: (state) => state.team.team,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            isUserAdmin: "team/isUserAdmin",
        }),
        getItemStatus() {
            return `item.status`;
        },
    },
    async created() {
        this.$store.commit("setTitle", "Évènements de groupe");
        this.$store.commit("setColor", "default");
        if (!this.team || this.$route.params.id !== this.team.id) {
            await this.refreshTeam(this.$route.params.id);
        }
    },
    methods: {
        async refreshTeam(id) {
            this.$store.commit("team/removeTeam");
            try {
                await this.$store.dispatch("team/getTeamById", id);
            } catch {
                this.$router.push({ name: "Home" });
            }
        },
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
