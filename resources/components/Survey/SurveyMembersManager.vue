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
                            :input-value="belongToSurvey(member.id)"
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
    name: "SurveyMembersManager",
    data: () => ({
        ajaxPending: false,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            survey: (state) => state.survey.survey,
            team: (state) => state.team.team,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    methods: {
        belongToSurvey(id) {
            return (
                this.survey.members.filter((member) => member.id === id)
                    .length > 0
            );
        },
        toggleParticipation(id) {
            if (this.belongToSurvey(id)) {
                this.removeMember(id);
            } else {
                this.addMember(id);
            }
        },
        async addMember(id) {
            try {
                await this.$store.dispatch("survey/addMemberToSurvey", id);
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
                this.$store.dispatch("survey/removeMemberFromSurvey", id);
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Membre supprimé",
                    },
                });
                document.dispatchEvent(event);
            } catch (e) {
                console.log(e);
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
