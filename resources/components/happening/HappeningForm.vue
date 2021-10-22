<template>
    <div>
        <v-text-field v-model="name" label="Nom" :rules="[rules.required]" />

        <v-textarea v-model="description" label="Description" />

        <v-textarea v-model="place" label="Lieu" />

        <div class="d-flex">
            <v-text-field
                :value="new Date(startDate).toLocaleDateString('fr-FR')"
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
                :value="new Date(endDate).toLocaleDateString('fr-FR')"
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

        <v-select
            label="Status"
            :disabled="ajaxPending"
            :items="projectStatus"
            v-model="status"
        />

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
                :disabled="disablingButton"
                :loading="ajaxPending"
                @click="validateHappening"
            >
                <v-icon> mdi-check </v-icon>
            </v-btn>
        </div>

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
import dayjs from "dayjs";
import { formValidators } from "../../services/formValidators";
import { mapState } from "vuex";

export default {
    name: "HappeningForm",
    data: () => ({
        valid: true,
        name: null,
        description: null,
        place: null,
        startDate: null,
        startDateMenuOpen: false,
        startTime: null,
        startTimeMenuOpen: false,
        endDate: null,
        endDateMenuOpen: false,
        endTime: null,
        endTimeMenuOpen: false,
        status: null,
        ajaxPending: false,
        rules: formValidators,
        projectStatus: [
            { value: "project", text: "Projet" },
            { value: "validated", text: "Validé" },
            { value: "canceled", text: "Annulé" },
        ],
    }),
    computed: {
        ...mapState({
            happening: (state) => state.happening.happening,
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
    created() {
        this.name = this.happening.name;
        this.description = this.happening.description;
        this.place = this.happening.place;
        this.startDate = dayjs(this.happening.start_at)
            .utc()
            .format("YYYY-MM-DD");
        this.startTime = dayjs(this.happening.start_at).utc().format("HH:mm");
        this.endDate = dayjs(this.happening.end_at).utc().format("YYYY-MM-DD");
        this.endTime = dayjs(this.happening.end_at).utc().format("HH:mm");
        this.status = this.happening.status;
    },
    methods: {
        cancelChange() {
            this.$emit("close");
        },
        async validateHappening() {
            try {
                this.ajaxPending = true;
                this.$store.dispatch("happening/updateHappening", {
                    id: this.happening.id,
                    name: this.name,
                    description: this.description,
                    place: this.place,
                    startAt: this.startDate + " " + this.startTime,
                    endAt: this.endDate + " " + this.endTime,
                    status: this.status,
                });
                await this.$store.dispatch("user/getUserWithToken");
                await this.$store.dispatch(
                    "team/getTeamById",
                    this.happening.team.id
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
