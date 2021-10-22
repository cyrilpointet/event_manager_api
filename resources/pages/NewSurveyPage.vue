<template>
    <div class="mainPage">
        <div>
            <v-form ref="form" v-model="valid">
                <v-card class="rounded-xl">
                    <v-card-title class="survey black--text"
                        >Sondage</v-card-title
                    >
                    <v-card-text>
                        <v-text-field
                            v-model="name"
                            label="Nom"
                            :rules="[rules.required]"
                        />

                        <v-textarea
                            rows="2"
                            auto-grow
                            v-model="description"
                            label="Description"
                        />

                        <v-textarea
                            rows="2"
                            auto-grow
                            v-model="place"
                            label="Lieu"
                        />
                        <p v-if="dates.length < 1" class="text-center">
                            Ajoutez au moins une date avec la fenêtre "Dates"
                            ci-dessous
                        </p>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="secondary"
                            rounded
                            min-width="100%"
                            :disabled="disablingButton"
                            :loading="ajaxPending"
                            @click="createSurvey"
                        >
                            Créer
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </div>

        <div class="calendar">
            <Calendar />
        </div>

        <div>
            <v-card class="rounded-xl">
                <v-card-title class="datetimes black--text">Dates</v-card-title>
                <v-card-text>
                    <NewSurveyDates />
                </v-card-text>
            </v-card>
        </div>

        <div>
            <v-card class="rounded-xl">
                <v-card-title class="user black--text">
                    Membres participants
                </v-card-title>
                <v-card-text>
                    <MembersSelector />
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
import { formValidators } from "../services/formValidators";
import { mapGetters, mapState } from "vuex";
import NewSurveyDates from "../components/Survey/NewSurveyDates";
import MembersSelector from "../components/common/MembersSelector";
import Calendar from "../components/common/Calendar";

export default {
    name: "NewSurveyPage",
    components: { NewSurveyDates, MembersSelector, Calendar },
    data: () => ({
        valid: true,
        name: "",
        description: "",
        place: "",
        ajaxPending: false,
        rules: formValidators,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            team: (state) => state.team.team,
            members: (state) => state.form.members,
            dates: (state) => state.form.dates,
        }),
        ...mapGetters({
            isUserAdmin: "team/isUserAdmin",
            selectedMembersIds: "common/selectedMembersIds",
        }),
        disablingButton() {
            return !this.valid || this.dates.length < 1;
        },
    },
    async created() {
        this.$store.commit("setTitle", "Nouveau sondage");
        this.$store.commit("setColor", "survey");
        this.$store.commit("team/removeTeam");
        try {
            await this.$store.dispatch(
                "team/getTeamById",
                this.$route.params.id
            );
        } catch {
            this.$router.push({ name: "Home" });
        }
        if (!this.isUserAdmin) {
            this.$router.push({ name: "Home" });
        }
        this.$store.commit("form/resetMembers");
        this.team.members.forEach((member) => {
            this.$store.commit("form/addMember", {
                name: member.name,
                id: member.id,
                status: true,
            });
        });
    },
    methods: {
        async createSurvey() {
            const newSurvey = {
                name: this.name,
                description: this.description,
                place: this.place,
                dates: this.dates,
                members: this.selectedMembersIds,
            };
            try {
                const storedSurvey = await this.$store.dispatch(
                    "survey/createSurvey",
                    {
                        survey: newSurvey,
                        teamId: this.team.id,
                    }
                );
                await this.$store.dispatch("user/getUserWithToken");
                this.$router.push({
                    name: "SurveyPage",
                    params: { id: storedSurvey.id },
                });
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
    },
};
</script>

<style lang="scss" scoped>
.calendar {
    grid-row-start: 4;
    @media (min-width: 960px) {
        grid-row-start: auto;
    }
}
</style>
