<template>
    <div style="overflow: hidden">
        <h4 class="mY-2" v-if="dates.length > 0">Dates sélectionnées</h4>
        <transition-group name="list">
            <div
                v-for="(date, index) in dates"
                :key="'date' + index"
                class="py-2 list-complete-item white"
            >
                <div class="d-flex align-center justify-space-between">
                    <div>
                        <h6>Date {{ index + 1 }}</h6>
                        <p class="body-1">
                            Début: {{ getFormatedDate(date.start) }}
                        </p>
                        <p class="body-1">
                            Fin: {{ getFormatedDate(date.end) }}
                        </p>
                    </div>
                    <v-btn icon color="error" @click="removeDate(index)">
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </div>
                <v-divider />
            </div>
        </transition-group>
        <div>
            <h4 class="mb-2">Nouvelle date</h4>
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
            <v-btn
                outlined
                rounded
                min-width="100%"
                color="secondary"
                class="mt-2"
                :disabled="isInvalid"
                @click="addDate"
            >
                Ajouter
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
import { mapState } from "vuex";

export default {
    name: "NewSurveyDates",
    data: () => ({
        startDate: dayjs().utc().format("YYYY-MM-DD"),
        startDateMenuOpen: false,
        startTime: dayjs().utc().format("HH:mm"),
        startTimeMenuOpen: false,
        endDate: dayjs().utc().format("YYYY-MM-DD"),
        endDateMenuOpen: false,
        endTime: dayjs().utc().format("HH:mm"),
        endTimeMenuOpen: false,
    }),
    computed: {
        ...mapState({
            dates: (state) => state.form.dates,
        }),
        isInvalid: function () {
            const start = new Date(this.startDate + " " + this.startTime);
            const end = new Date(this.endDate + " " + this.endTime);
            return end < start;
        },
    },
    created() {
        this.$store.commit("form/resetDates");
    },
    methods: {
        getFormatedDate(date) {
            return dayjs(date).utc().format("DD-MM-YYYY à HH:mm");
        },
        addDate() {
            this.$store.commit("form/addDate", {
                start: this.startDate + " " + this.startTime,
                end: this.endDate + " " + this.endTime,
            });
            this.startDate = dayjs().utc().format("YYYY-MM-DD");
            this.startTime = dayjs().utc().format("HH:mm");
            this.endDate = dayjs().utc().format("YYYY-MM-DD");
            this.endTime = dayjs().utc().format("HH:mm");
        },
        removeDate(index) {
            this.$store.commit("form/removeDate", index);
        },
    },
};
</script>
