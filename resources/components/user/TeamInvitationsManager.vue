<template>
    <div>
        <template v-for="(invitation, index) in user.invitations">
            <div
                v-if="invitation.isFromTeam === true"
                :key="invitation.id"
                class="d-flex flex-wrap align-center"
                :class="
                    index !== user.invitations.length - 1 ? 'underline' : ''
                "
            >
                <p class="flex-grow-1 ma-0">{{ invitation.team.name }}</p>
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
                                invitation.team.name
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
                                invitation.team.name
                            )
                        "
                    >
                        <v-icon dark> mdi-cancel </v-icon>
                    </v-btn>
                </div>
            </div>
        </template>
        <div v-if="user.invitations.length < 1">
            <p>Aucune demande en cours</p>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import { ApiConsumer } from "../../services/ApiConsumer";
import { User } from "../../models/User";

export default {
    name: "TeamInvitationsManager",
    data: () => {
        return {
            ajaxPending: false,
        };
    },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
    },
    methods: {
        askManageInvitation(invitationId, status, name) {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `${status ? "Accepter" : "Refuser"} l'invitation ?`,
                    text: status
                        ? `Vous rejoindrez ${name}.`
                        : `Vous ne rejoindrez pas ${name}.`,
                    callback: () => {
                        this.manageInvitation(invitationId, status);
                    },
                },
            });
            document.dispatchEvent(event);
        },
        async manageInvitation(invitationId, status) {
            this.ajaxPending = true;
            const updatedUser = await ApiConsumer.put(
                `user/invitation/${invitationId}`,
                {
                    status: status,
                }
            );
            this.$store.commit("user/setUser", new User(updatedUser));
            this.ajaxPending = false;
        },
    },
};
</script>

<style scoped lang="scss">
.underline {
    border-bottom: 1px solid lightgray;
}
</style>
