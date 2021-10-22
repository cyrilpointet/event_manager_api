<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Nom</th>
                    <th class="text-left">Participation</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="member in team.members" :key="member.id">
                    <td>{{ member.name }}</td>
                    <td>
                        <v-switch
                            :input-value="belongToHappening(member.id)"
                            :disabled="member.id === user.id || ajaxPending"
                            @change="toggleParticipation(member.id)"
                        />
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
    name: "HappeningMembersManager",
    data: () => ({
        ajaxPending: false,
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
    methods: {
        belongToHappening(id) {
            return (
                this.happening.members.filter((member) => member.id === id)
                    .length > 0
            );
        },
        toggleParticipation(id) {
            if (this.belongToHappening(id)) {
                this.removeMember(id);
            } else {
                this.addMember(id);
            }
        },
        async addMember(id) {
            try {
                await this.$store.dispatch(
                    "happening/addMemberToHappening",
                    id
                );
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Membre ajouté",
                    },
                });
                document.dispatchEvent(event);
            } catch {
                this.ajaxPending = false;
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Oups... Une erreur est survenue !",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
        },
        removeMember(id) {
            try {
                this.$store.dispatch("happening/removeMemberFromHappening", id);
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Membre supprimé",
                    },
                });
                document.dispatchEvent(event);
            } catch {
                this.ajaxPending = false;
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
