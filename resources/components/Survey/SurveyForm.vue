<template>
    <div>
        <v-text-field v-model="name" label="Nom" :rules="[rules.required]" />

        <v-textarea v-model="description" label="Description" />

        <v-textarea v-model="place" label="Lieu" />

        <div class="d-flex justify-space-between">
            <v-btn
                color="error"
                rounded
                :loading="ajaxPending"
                @click="cancelChange"
            >
                <v-icon dark> mdi-cancel </v-icon>
            </v-btn>

            <v-btn
                color="secondary"
                rounded
                dark
                :disabled="!this.valid"
                :loading="ajaxPending"
                @click="updateSurvey"
            >
                <v-icon> mdi-check </v-icon>
            </v-btn>
        </div>
    </div>
</template>

<script>
import { formValidators } from "../../services/formValidators";
import { mapState } from "vuex";

export default {
    name: "SurveyForm",
    data: () => ({
        valid: true,
        name: null,
        description: null,
        place: null,
        ajaxPending: false,
        rules: formValidators,
    }),
    computed: {
        ...mapState({
            survey: (state) => state.survey.survey,
        }),
    },
    created() {
        this.name = this.survey.name;
        this.description = this.survey.description;
        this.place = this.survey.place;
    },
    methods: {
        cancelChange() {
            this.$emit("close");
        },
        async updateSurvey() {
            try {
                this.ajaxPending = true;
                this.$store.dispatch("survey/updateSurvey", {
                    id: this.survey.id,
                    name: this.name,
                    description: this.description,
                    place: this.place,
                });
                await this.$store.dispatch("user/getUserWithToken");
                await this.$store.dispatch(
                    "team/getTeamById",
                    this.survey.team.id
                );
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
            this.ajaxPending = false;
            this.$emit("close");
        },
    },
};
</script>
