<template>
    <div class="home">
        <div v-if="!isLogged || !user" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
        </div>
        <div v-else class="mainPage">
            <div>
                <v-expansion-panels>
                    <v-expansion-panel v-if="user.invitations.length > 0">
                        <v-expansion-panel-header class="default">
                            <span>
                                <v-badge
                                    color="accent"
                                    :content="user.invitations.length"
                                >
                                    Invitations reçues
                                </v-badge>
                            </span>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content class="pt-4">
                            <TeamInvitationsManager />
                        </v-expansion-panel-content>
                    </v-expansion-panel>

                    <v-expansion-panel>
                        <v-expansion-panel-header class="default">
                            <span v-if="user.upcomingHappenings.length > 0">
                                <v-badge
                                    color="accent"
                                    :content="user.upcomingHappenings.length"
                                >
                                    Prochains évènements
                                </v-badge>
                            </span>
                            <span v-else>Evènements</span>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <NextUserHappenings />
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </div>

            <div>
                <Calendar />
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import NextUserHappenings from "../components/user/NextUserHappenings";
import TeamInvitationsManager from "../components/user/TeamInvitationsManager";
import Calendar from "../components/common/Calendar";

export default {
    name: "Home",
    components: { NextUserHappenings, TeamInvitationsManager, Calendar },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
        }),
    },
    created() {
        this.$store.commit("setTitle", "Accueil");
        this.$store.commit("setColor", "default");
    },
};
</script>

<style lang="scss">
.theme--light.v-expansion-panels
    .v-expansion-panel-header
    .v-expansion-panel-header__icon
    .v-icon {
    color: white !important;
}
</style>
