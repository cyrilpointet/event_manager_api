<template>
    <div>
        <div v-if="!isLogged || !survey" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
        </div>

        <div v-else class="mainContainer">
            <div class="mainCard">
                <v-card class="rounded-xl">
                    <v-card-title class="survey black--text">
                        {{ survey.name }}
                    </v-card-title>
                    <v-card-text>
                        <SurveyInfos v-if="!isEditing" />
                        <div v-if="isUserAdmin && !isEditing">
                            <v-btn
                                color="secondary"
                                outlined
                                rounded
                                min-width="100%"
                                class="mt-4"
                                @click="isEditing = !isEditing"
                            >
                                Editer
                            </v-btn>
                        </div>
                        <SurveyForm
                            v-if="isUserAdmin && isEditing"
                            @close="isEditing = false"
                        />
                    </v-card-text>
                </v-card>
            </div>

            <div class="calendar">
                <Calendar />
            </div>

            <div class="presences">
                <v-card class="rounded-xl">
                    <v-card-title class="user black--text">
                        Présences
                    </v-card-title>
                    <SurveyPresences />
                </v-card>
            </div>

            <div v-if="isLogged && isUserAdmin" class="memberManager">
                <v-card v-if="isUserAdmin" class="rounded-xl">
                    <v-card-title class="user black--text">
                        Gestion des membres
                    </v-card-title>
                    <SurveyMembersManager />
                </v-card>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import SurveyInfos from "../components/Survey/SurveyInfos";
import SurveyPresences from "../components/Survey/SurveyPresences";
import SurveyMembersManager from "../components/Survey/SurveyMembersManager";
import SurveyForm from "../components/Survey/SurveyForm";
import Calendar from "../components/common/Calendar";

export default {
    name: "SurveyPage",
    components: {
        SurveyInfos,
        SurveyPresences,
        SurveyMembersManager,
        SurveyForm,
        Calendar,
    },
    data: () => ({
        isEditing: false,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            team: (state) => state.team.team,
            survey: (state) => state.survey.survey,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    async created() {
        this.$store.commit("setTitle", "Sondage");
        this.$store.commit("setColor", "survey");
        if (!this.survey || this.$route.params.id !== this.survey.id) {
            await this.refreshSurvey(this.$route.params.id);
        }
        if (!this.team || this.survey.team.id !== this.team.id) {
            await this.refreshTeam(this.survey.team.id);
        }
    },
    async beforeRouteUpdate(to, from, next) {
        if (parseInt(from.params.id) !== to.params.id) {
            await this.refreshSurvey(to.params.id);
            if (!this.team || this.survey.team.id !== this.team.id) {
                await this.refreshTeam(this.survey.team.id);
            }
            next();
        }
    },
    methods: {
        async refreshSurvey(id) {
            this.$store.commit("survey/removeSurvey");
            try {
                await this.$store.dispatch("survey/getSurveyById", id);
            } catch {
                this.$router.push({ name: "Home" });
            }
        },
        async refreshTeam(id) {
            this.$store.commit("team/removeTeam");
            try {
                await this.$store.dispatch("team/getTeamById", id);
            } catch {
                this.$router.push({ name: "Home" });
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.mainContainer {
    display: grid;
    grid-gap: 1.5rem;
    grid-template-areas: "mainCard" "presences" "memberManager" "calendar";
    @media (min-width: 960px) {
        grid-template-areas: "mainCard calendar" "presences presences" "memberManager .";
    }
}
.mainCard {
    grid-area: mainCard;
}
.calendar {
    grid-area: calendar;
}
.presences {
    grid-area: presences;
}
.memberManager {
    grid-area: memberManager;
}
.comments {
    grid-area: comments;
}
</style>
