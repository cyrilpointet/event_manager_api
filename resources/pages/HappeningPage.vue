<template>
    <div>
        <div v-if="!isLogged || !happening" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
        </div>

        <div v-else class="mainContainer">
            <div class="mainCard">
                <v-card class="rounded-xl">
                    <v-card-title class="events black--text">
                        {{ happening.name }}
                        <v-spacer></v-spacer>
                        <v-chip
                            class="ml-4"
                            dark
                            :color="happeningStatus[happening.status].color"
                        >
                            {{ happeningStatus[happening.status].name }}
                        </v-chip>
                    </v-card-title>
                    <v-card-text>
                        <HappeningInfos v-if="!isEditing" />
                        <HappeningForm
                            v-if="isUserAdmin && isEditing"
                            @close="isEditing = false"
                        />
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
                            <v-btn
                                color="error"
                                outlined
                                rounded
                                min-width="100%"
                                class="mt-4"
                                @click="askDeleteHappening"
                            >
                                Supprimer
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </div>

            <div class="presences">
                <v-card class="rounded-xl">
                    <v-card-title class="user black--text"
                        >Présences</v-card-title
                    >
                    <v-card-text>
                        <MembersPresences />
                    </v-card-text>
                </v-card>
            </div>

            <div v-if="isLogged && isUserAdmin" class="memberManager">
                <v-card class="rounded-xl">
                    <v-card-title class="user black--text">
                        Gestion des membres
                    </v-card-title>
                    <v-card-text>
                        <HappeningMembersManager />
                    </v-card-text>
                </v-card>
            </div>

            <div class="comments">
                <v-card class="rounded-xl">
                    <v-card-title class="default black--text"
                        >Commentaires</v-card-title
                    >
                    <v-card-text>
                        <HappeningComments />
                    </v-card-text>
                </v-card>
            </div>

            <div class="calendar">
                <Calendar />
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import MembersPresences from "../components/happening/MembersPresences";
import HappeningInfos from "../components/happening/HappeningInfos";
import HappeningForm from "../components/happening/HappeningForm";
import HappeningMembersManager from "../components/happening/HappeningMembersManager";
import HappeningComments from "../components/happening/HappeningComments";
import { HAPPENING_STATUS } from "../constantes/HappeningStatus";
import Calendar from "../components/common/Calendar";

export default {
    name: "HappeningPage",
    components: {
        MembersPresences,
        HappeningInfos,
        HappeningForm,
        HappeningMembersManager,
        HappeningComments,
        Calendar,
    },
    data: () => ({
        isEditing: false,
        happeningStatus: HAPPENING_STATUS,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            happening: (state) => state.happening.happening,
            team: (state) => state.team.team,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    async created() {
        this.$store.commit("setTitle", "Evènement");
        this.$store.commit("setColor", "events");
        if (!this.happening || this.$route.params.id !== this.happening.id) {
            await this.refreshHappening(this.$route.params.id);
        }
        if (this.happening.surveyId) {
            this.$router.push({
                name: "SurveyPage",
                params: { id: this.happening.surveyId },
            });
        }
        if (!this.team || this.happening.team.id !== this.team.id) {
            await this.refreshTeam(this.happening.team.id);
        }
    },
    async beforeRouteUpdate(to, from, next) {
        if (parseInt(from.params.id) !== to.params.id) {
            await this.refreshHappening(to.params.id);
            if (!this.team || this.happening.team.id !== this.team.id) {
                await this.refreshTeam(this.happening.team.id);
            }
            next();
        }
    },
    methods: {
        async refreshHappening(id) {
            this.$store.commit("happening/removeHappening");
            try {
                await this.$store.dispatch("happening/getHappeningById", id);
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
        askDeleteHappening() {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `Supprimer cet évènement ?`,
                    text: "Cette action est irréversible",
                    callback: () => {
                        this.deleteHappening();
                    },
                },
            });
            document.dispatchEvent(event);
        },
        async deleteHappening() {
            try {
                await this.$store.dispatch("happening/deleteHappening");
                await this.$store.commit("team/removeTeam");
                await this.$store.dispatch("user/getUserWithToken");
                this.$router.push({ name: "Home" });
            } catch {
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Oups... Une erreur est survenue !",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.mainContainer {
    display: grid;
    grid-gap: 1.5rem;
    grid-template-areas: "mainCard" "presences" "memberManager" "comments" "calendar";
    @media (min-width: 960px) {
        grid-template-areas: "mainCard calendar" "presences comments" "memberManager .";
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
