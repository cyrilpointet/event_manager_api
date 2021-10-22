<template>
    <div>
        <template v-for="(invitation, index) in team.invitations">
            <div
                :key="invitation.id"
                class="d-flex flex-wrap align-center py-2"
                :class="
                    index !== team.invitations.length - 1 ? 'underline' : ''
                "
            >
                <p class="flex-grow-1 ma-0">{{ invitation.userEmail }}</p>
                <div>
                    <v-btn
                        color="secondary"
                        rounded
                        dark
                        :disabled="ajaxPending"
                        @click="
                            askManageInvitation(
                                invitation.id,
                                true,
                                invitation.userEmail
                            )
                        "
                        class="mr-4"
                    >
                        <v-icon> mdi-check </v-icon>
                    </v-btn>

                    <v-btn
                        color="error"
                        rounded
                        :disabled="ajaxPending"
                        @click="
                            askManageInvitation(
                                invitation.id,
                                false,
                                invitation.userEmail
                            )
                        "
                    >
                        <v-icon dark> mdi-cancel </v-icon>
                    </v-btn>
                </div>
            </div>
        </template>
        <div v-if="team.invitations.length < 1">
            <p>Aucune demande en cours</p>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import { ApiConsumer } from "../../services/ApiConsumer";
import { Team } from "../../models/Team";

export default {
    name: "UserInvitationsManager",
    data: () => {
        return {
            ajaxPending: false,
        };
    },
    computed: {
        ...mapState({
            team: (state) => state.team.team,
        }),
    },
    methods: {
        askManageInvitation(invitationId, status, name) {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `${status ? "Accepter" : "Rejeter"} la demande ?`,
                    text: status
                        ? `${name} rejoindra le groupe.`
                        : `${name} ne rejoindra pas le groupe.`,
                    callback: () => {
                        this.manageInvitation(invitationId, status);
                    },
                },
            });
            document.dispatchEvent(event);
        },
        async manageInvitation(invitationId, status) {
            this.ajaxPending = true;
            try {
                const updatedTeam = await ApiConsumer.put(
                    `team/${this.team.id}/invitation`,
                    {
                        id: invitationId,
                        status: status,
                    }
                );
                this.$store.commit("team/setTeam", new Team(updatedTeam));
                this.ajaxPending = false;
            } catch {
                this.$store.commit("team/removeTeam");
                this.$router.push({ name: "Home" });
            }
        },
    },
};
</script>

<style scoped lang="scss">
.underline {
    border-bottom: 1px solid lightgray;
}
</style>
