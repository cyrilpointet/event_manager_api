<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Nom</th>
                    <th class="text-left">Administrateur</th>
                    <th class="text-left">Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="member in team.members" :key="member.id">
                    <td>{{ member.name }}</td>
                    <td>
                        <v-switch
                            :input-value="member.isAdmin"
                            :disabled="
                                (team.adminCount < 2 &&
                                    user.id === member.id) ||
                                ajaxPending
                            "
                            @change="askToggleAdmin(member)"
                        />
                    </td>
                    <td>
                        <v-btn
                            icon
                            v-if="isUserAdmin && member.id !== user.id"
                            color="error"
                            @click="askRemoveMember(member)"
                            :disabled="ajaxPending"
                        >
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import { ApiConsumer } from "../../services/ApiConsumer";
import { Team } from "../../models/Team";

export default {
    name: "MembersManager",
    data: () => {
        return {
            ajaxPending: false,
        };
    },
    computed: {
        ...mapState({
            team: (state) => state.team.team,
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    methods: {
        askToggleAdmin(member) {
            if (member.id !== this.user.id) {
                this.toggleAdmin(member);
            } else {
                const event = new CustomEvent("confirmAction", {
                    detail: {
                        title: `Renoncer à l'administration de ce groupe ?`,
                        text: "Vous ne pourrez plus éditer ce groupe, ni ses évènements.",
                        callback: () => {
                            this.toggleAdmin(member);
                        },
                    },
                });
                document.dispatchEvent(event);
            }
        },
        async toggleAdmin(member) {
            this.ajaxPending = true;
            try {
                const team = await ApiConsumer.put(
                    `team/${this.team.id}/admin`,
                    {
                        id: member.id,
                        admin: !member.isAdmin,
                    }
                );
                this.$store.commit("team/setTeam", new Team(team));
            } catch (error) {
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Oups... Une erreur est survenue !",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
            this.ajaxPending = false;
        },

        askRemoveMember(member) {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `Supprimer ${member.name} du groupe ?`,
                    text: "Cette action est irréversible",
                    callback: () => {
                        this.removeMember(member);
                    },
                },
            });
            document.dispatchEvent(event);
        },

        async removeMember(member) {
            this.ajaxPending = true;
            try {
                const team = await ApiConsumer.delete(
                    `team/${this.team.id}/member`,
                    {
                        id: member.id,
                    }
                );
                this.$store.commit("team/setTeam", new Team(team));
            } catch {
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Oups... Une erreur est survenue !",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
            this.ajaxPending = false;
        },
    },
};
</script>
