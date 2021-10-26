<template>
    <div>
        <div v-if="!isLogged || !team" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
        </div>

        <div v-else class="mainContainer">
            <div class="mainCard">
                <v-card class="rounded-xl">
                    <v-card-title class="team black--text">{{
                        team.name
                    }}</v-card-title>
                    <v-card-text class="mt-2">
                        <div v-if="isUserAdmin">
                            <v-btn
                                color="secondary"
                                outlined
                                rounded
                                min-width="100%"
                                @click="
                                    $router.push({
                                        name: 'NewHappeningPage',
                                        params: { id: team.id },
                                    })
                                "
                            >
                                Ajouter un évènement
                            </v-btn>
                            <v-btn
                                color="secondary"
                                outlined
                                rounded
                                min-width="100%"
                                class="mt-4"
                                @click="
                                    $router.push({
                                        name: 'NewSurveyPage',
                                        params: { id: team.id },
                                    })
                                "
                            >
                                Ajouter un sondage
                            </v-btn>
                            <v-btn
                                color="secondary"
                                outlined
                                rounded
                                min-width="100%"
                                @click="openDialog"
                                class="mt-4"
                            >
                                Editer
                            </v-btn>
                            <v-btn
                                outlined
                                rounded
                                min-width="100%"
                                color="error"
                                @click="askDeleteTeam"
                                class="mt-4"
                            >
                                Supprimer
                            </v-btn>
                        </div>
                        <v-btn
                            v-if="!isUserAdmin"
                            outlined
                            rounded
                            min-width="100%"
                            color="error"
                            @click="askLeaveTeam"
                            class="mt-4"
                        >
                            Quitter
                        </v-btn>
                    </v-card-text>
                </v-card>
            </div>

            <div class="invitationsCard">
                <v-card
                    v-if="isUserAdmin && team.invitations.length > 0"
                    class="rounded-xl"
                >
                    <v-card-title class="user black--text">
                        Demandes d'adhésion
                    </v-card-title>
                    <v-card-text>
                        <UserInvitationsManager />
                    </v-card-text>
                </v-card>
            </div>

            <div class="eventsCard">
                <v-card class="rounded-xl">
                    <v-card-title class="events black--text">
                        <span>
                            <v-badge
                                color="accent"
                                :content="
                                    user.getUpcommingHappeningsByTeam(team.id)
                                        .length
                                "
                            >
                                Prochains évènements
                            </v-badge>
                        </span>
                    </v-card-title>
                    <v-card-text>
                        <NextTeamHappenings />
                    </v-card-text>
                </v-card>
            </div>

            <div class="membersCard">
                <v-card class="rounded-xl">
                    <v-card-title class="user black--text">
                        Membres
                    </v-card-title>
                    <v-card-text>
                        <MembersViewer v-if="!isUserAdmin" />
                        <MembersManager v-if="isUserAdmin" />
                        <div v-if="isUserAdmin">
                            <v-divider />
                            <h3 class="mt-6">Ajouter un membre</h3>
                            <UserFinder />
                        </div>
                    </v-card-text>
                </v-card>
            </div>

            <div class="calendarCard">
                <Calendar />
            </div>
        </div>

        <v-dialog v-model="isDialogOpen" width="500">
            <v-card v-if="isUserAdmin && team" class="rounded-xl">
                <v-card-title class="default black--text"
                    >Nouveau nom</v-card-title
                >
                <v-card-text class="pt-4">
                    <v-text-field v-model="newName" label="Nom" />
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        :disabled="!newName"
                        color="secondary"
                        dark
                        @click="updateTeam"
                        >Valider</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import { ApiConsumer } from "../services/ApiConsumer";
import UserInvitationsManager from "../components/team/UserInvitationsManager";
import MembersViewer from "../components/team/MembersViewer";
import MembersManager from "../components/team/MembersManager";
import UserFinder from "../components/team/UserFinder";
import NextTeamHappenings from "../components/team/NextTeamHappenings";
import Calendar from "../components/common/Calendar";

export default {
    name: "TeamPage",
    components: {
        MembersViewer,
        MembersManager,
        UserFinder,
        UserInvitationsManager,
        NextTeamHappenings,
        Calendar,
    },
    data: function () {
        return {
            isDialogOpen: false,
            newName: "",
        };
    },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            team: (state) => state.team.team,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    async created() {
        this.$store.commit("setTitle", "Groupe");
        this.$store.commit("setColor", "team");
        if (!this.team || this.$route.params.id !== this.team.id) {
            await this.refreshTeam(this.$route.params.id);
        }
    },
    async beforeRouteUpdate(to, from, next) {
        if (parseInt(from.params.id) !== to.params.id) {
            await this.refreshTeam(to.params.id);
            next();
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
        askDeleteTeam() {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `Supprimer ce groupe ?`,
                    text: "Cette action est irréversible",
                    callback: () => {
                        this.deleteTeam();
                    },
                },
            });
            document.dispatchEvent(event);
        },

        async deleteTeam() {
            try {
                await ApiConsumer.delete("team/" + this.team.id);
                this.$store.commit("user/removeTeam", this.team.id);
                this.$store.commit("team/removeTeam");
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

        askLeaveTeam() {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `Quitter ce groupe ?`,
                    text: "Cette action est irréversible",
                    callback: () => {
                        this.leaveTeam();
                    },
                },
            });
            document.dispatchEvent(event);
        },

        async leaveTeam() {
            try {
                await ApiConsumer.delete(`user/team/${this.team.id}`);
                this.$store.commit("user/removeTeam", this.team.id);
                this.$store.commit("team/removeTeam");
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

        openDialog() {
            this.newName = this.team.name;
            this.isDialogOpen = true;
        },

        async updateTeam() {
            try {
                await this.$store.dispatch("team/updateTeam", {
                    id: this.team.id,
                    name: this.newName,
                });
                await this.$store.dispatch("user/getUserWithToken");
            } catch {
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Oups... Une erreur est survenue !",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
            this.isDialogOpen = false;
        },
    },
};
</script>

<style lang="scss" scoped>
.mainContainer {
    display: grid;
    grid-gap: 1.5rem;
    grid-template-columns: 1fr;
    grid-template-areas: "mainCard" "invitationsCard" "eventsCard" "membersCard" "calendarCard";
    @media (min-width: 960px) {
        grid-template-columns: repeat(2, 1fr);
        grid-template-areas: "mainCard calendarCard" "invitationsCard calendarCard" "membersCard eventsCard";
    }
}
.mainCard {
    grid-area: mainCard;
}
.invitationsCard {
    grid-area: invitationsCard;
}
.eventsCard {
    grid-area: eventsCard;
}
.membersCard {
    grid-area: membersCard;
}
.calendarCard {
    grid-area: calendarCard;
}
</style>
