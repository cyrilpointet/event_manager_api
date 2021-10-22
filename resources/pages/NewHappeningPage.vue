<template>
    <div>
        <v-form ref="form" v-model="valid" class="mainPage">
            <div class="form">
                <v-card class="rounded-xl">
                    <v-card-title class="events black--text"
                        >Evènement</v-card-title
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

                        <div class="d-flex">
                            <v-text-field
                                :value="
                                    new Date(startDate).toLocaleDateString(
                                        'fr-FR'
                                    )
                                "
                                label="Date de début"
                                prepend-icon="mdi-calendar"
                                readonly
                                @click="startDateMenuOpen = true"
                            />
                            <v-text-field
                                v-model="startTime"
                                label="Heure de début"
                                prepend-icon="mdi-clock-time-four-outline"
                                readonly
                                @click="startTimeMenuOpen = true"
                            />
                        </div>
                        <div class="d-flex">
                            <v-text-field
                                :value="
                                    new Date(endDate).toLocaleDateString(
                                        'fr-FR'
                                    )
                                "
                                label="Date de fin"
                                prepend-icon="mdi-calendar"
                                readonly
                                @click="endDateMenuOpen = true"
                            />
                            <v-text-field
                                v-model="endTime"
                                label="Heure de fin"
                                prepend-icon="mdi-clock-time-four-outline"
                                readonly
                                @click="endTimeMenuOpen = true"
                            />
                        </div>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="secondary"
                            rounded
                            min-width="100%"
                            :disabled="disablingButton"
                            :loading="ajaxPending"
                            @click="validateHappening"
                        >
                            Créer
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </div>

            <div class="calendar">
                <Calendar />
            </div>

            <div>
                <v-card class="rounded-xl">
                    <v-card>
                        <v-card-title class="user black--text">
                            Membres participants
                        </v-card-title>
                        <v-card-text>
                            <MembersSelector />
                        </v-card-text>
                    </v-card>
                </v-card>
            </div>
        </v-form>

        <v-dialog v-model="startDateMenuOpen" width="unset">
            <div>
                <v-date-picker
                    v-model="startDate"
                    locale="fr"
                    @input="startDateMenuOpen = false"
                />
            </div>
        </v-dialog>

        <v-dialog v-model="startTimeMenuOpen" width="unset">
            <div>
                <v-time-picker
                    v-model="startTime"
                    locale="fr"
                    format="24hr"
                    @click:minute="startTimeMenuOpen = false"
                />
            </div>
        </v-dialog>

        <v-dialog v-model="endDateMenuOpen" width="unset">
            <div>
                <v-date-picker
                    v-model="endDate"
                    locale="fr"
                    @input="endDateMenuOpen = false"
                />
            </div>
        </v-dialog>

        <v-dialog v-model="endTimeMenuOpen" width="unset">
            <div>
                <v-time-picker
                    v-model="endTime"
                    locale="fr"
                    format="24hr"
                    @click:minute="endTimeMenuOpen = false"
                />
            </div>
        </v-dialog>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import dayjs from "dayjs";
import { formValidators } from "../services/formValidators";
import MembersSelector from "../components/common/MembersSelector";
import Calendar from "../components/common/Calendar";

export default {
    name: "NewHappeningPage",
    components: { MembersSelector, Calendar },
    data: () => ({
        valid: true,
        name: "",
        description: "",
        place: "",
        startDate: dayjs().utc().format("YYYY-MM-DD"),
        startDateMenuOpen: false,
        startTime: dayjs().utc().format("HH:mm"),
        startTimeMenuOpen: false,
        endDate: dayjs().utc().format("YYYY-MM-DD"),
        endDateMenuOpen: false,
        endTime: dayjs().utc().format("HH:mm"),
        endTimeMenuOpen: false,
        ajaxPending: false,
        rules: formValidators,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            team: (state) => state.team.team,
            members: (state) => state.form.members,
        }),
        ...mapGetters({
            isUserAdmin: "team/isUserAdmin",
            selectedMembersIds: "common/selectedMembersIds",
        }),
        disablingButton: function () {
            const start = new Date(this.startDate + " " + this.startTime);
            const end = new Date(this.endDate + " " + this.endTime);
            return (
                !this.valid ||
                !this.startDate ||
                !this.startTime ||
                !this.endDate ||
                !this.endTime ||
                start > end
            );
        },
    },
    async created() {
        this.$store.commit("setTitle", "Nouvel évènement");
        this.$store.commit("setColor", "events");
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
        async validateHappening() {
            const newHappening = {
                name: this.name,
                description: this.description,
                place: this.place,
                startAt: this.startDate + " " + this.startTime,
                endAt: this.endDate + " " + this.endTime,
                members: this.selectedMembersIds,
            };
            try {
                const storedHappening = await this.$store.dispatch(
                    "happening/createHappening",
                    {
                        happening: newHappening,
                        teamId: this.team.id,
                    }
                );
                await this.$store.dispatch("user/getUserWithToken");
                this.$router.push({
                    name: "HappeningPage",
                    params: { id: storedHappening.id },
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
.form {
    @media (min-width: 960px) {
        grid-row-start: 1;
        grid-row-end: 3;
    }
}
.calendar {
    grid-row-start: 3;
    @media (min-width: 960px) {
        grid-row-start: auto;
    }
}
</style>
