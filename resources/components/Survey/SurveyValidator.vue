<template>
    <tr>
        <td class="pa-2">
            <v-btn color="secondary" rounded @click="askValidate"
                >Valider</v-btn
            >
        </td>
        <template v-for="happening in happeningStatus">
            <td :key="happening.id">
                <span class="d-flex justify-center">
                    <v-checkbox v-model="happening.validated" />
                </span>
            </td>
        </template>
    </tr>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "SurveyValidator",
    data: () => ({
        ajaxPending: false,
        happeningStatus: [],
    }),
    computed: {
        ...mapState({
            survey: (state) => state.survey.survey,
            team: (state) => state.team.team,
        }),
    },
    created() {
        this.survey.happenings.forEach((happening) => {
            this.happeningStatus.push({
                id: happening.id,
                validated: false,
            });
        });
    },
    methods: {
        askValidate() {
            const event = new CustomEvent("confirmAction", {
                detail: {
                    title: `Valider les dates sélectionnées ?`,
                    text: "Cela cloturera le sondage et effacera toutes les dates non-sélectionnées.",
                    callback: () => {
                        this.validateSurvey();
                    },
                },
            });
            document.dispatchEvent(event);
        },
        async validateSurvey() {
            const validatedIds = [];
            this.happeningStatus.forEach((happening) => {
                if (happening.validated === true) {
                    validatedIds.push(happening.id);
                }
            });
            await this.$store.dispatch("survey/validateSurvey", validatedIds);
            await this.$store.dispatch("user/getUserWithToken");
            const teamId = this.team.id;
            this.$store.commit("team/removeTeam");
            this.$router.push({ name: "Team", params: { id: teamId } });
        },
    },
};
</script>
