<template>
    <div class="mainPage">
        <div>
            <v-card class="rounded-xl">
                <v-card-title class="user black--text">
                    {{ user.name }}
                </v-card-title>
                <v-card-text class="pt-4">
                    <p>Email: {{ user.email }}</p>
                    <p>Inscription: {{ user.createdAt }}</p>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        outlined
                        rounded
                        min-width="100%"
                        @click="logout"
                        color="error"
                    >
                        Déconnexion
                    </v-btn>
                </v-card-actions>
            </v-card>
        </div>

        <div class="calendar">
            <Calendar />
        </div>

        <div class="nextHappenings">
            <v-card class="rounded-xl">
                <v-card-title class="events black--text">
                    <span v-if="user.upcomingHappenings.length > 0">
                        <v-badge
                            color="accent"
                            :content="user.upcomingHappenings.length"
                        >
                            Prochains évènements
                        </v-badge>
                    </span>
                    <span v-else>Evènements</span>
                </v-card-title>
                <v-card-text>
                    <NextUserHappenings />
                </v-card-text>
            </v-card>
        </div>

        <div>
            <v-card class="rounded-xl">
                <v-card-title class="team black--text"
                    >Mes groupes</v-card-title
                >
                <v-card-text>
                    <TeamsViewer />
                    <v-divider />
                    <h3 class="mt-6">Chercher un groupe</h3>
                    <TeamFinder />
                </v-card-text>
            </v-card>
        </div>

        <div class="invitations">
            <v-card v-if="user.invitations.length > 0" class="rounded-xl">
                <v-card-title class="team black--text">
                    Invitations reçues
                </v-card-title>
                <v-card-text class="pt-4">
                    <TeamInvitationsManager />
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import TeamsViewer from "./TeamsViewer";
import TeamInvitationsManager from "./TeamInvitationsManager";
import NextUserHappenings from "./NextUserHappenings";
import Calendar from "../common/Calendar";
import TeamFinder from "./TeamFinder";

export default {
    name: "Account",
    components: {
        TeamsViewer,
        TeamInvitationsManager,
        NextUserHappenings,
        Calendar,
        TeamFinder,
    },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
        }),
    },
    created() {
        this.$store.commit("setColor", "user");
    },
    methods: {
        logout() {
            this.$store.dispatch("user/logout");
            if (this.$route.name !== "account") {
                this.$router.push({ name: "Account" });
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.invitations {
    grid-row: 2 / 3;
    @media (min-width: 960px) {
        grid-row: auto;
    }
}
.calendar {
    grid-row: 5 / 6;
    @media (min-width: 960px) {
        grid-row: auto;
    }
}
.nextHappenings {
    @media (min-width: 960px) {
        grid-row: 2 / 4;
    }
}
</style>
