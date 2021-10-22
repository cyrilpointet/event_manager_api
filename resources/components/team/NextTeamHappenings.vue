<template>
    <v-list dense>
        <template v-for="(happening, index) in happenings">
            <v-divider :key="'divider' + happening.id" v-if="index !== 0" />
            <v-list-item :key="happening.id" @click="goToHappening(happening)">
                <v-list-item-content>
                    <v-list-item-title>{{ happening.name }}</v-list-item-title>
                    <v-list-item-subtitle>
                        {{ happening.team.name }}
                    </v-list-item-subtitle>
                    <v-list-item-subtitle>
                        {{ happening.startAt }}
                    </v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-icon class="align-self-center">
                    <v-icon> mdi-chevron-right </v-icon>
                </v-list-item-icon>
            </v-list-item>
        </template>

        <v-divider />
        <v-list-item
            v-ripple
            style="cursor: pointer"
            @click="
                $router.push({
                    name: 'TeamHappeningsPage',
                    params: { id: team.id },
                })
            "
        >
            <v-list-item-title>Tout voir</v-list-item-title>
            <v-list-item-icon class="align-self-center">
                <v-icon> mdi-chevron-right </v-icon>
            </v-list-item-icon>
        </v-list-item>
    </v-list>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "NextTeamHappenings",
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            team: (state) => state.team.team,
        }),
    },
    data: () => ({
        happenings: [],
    }),
    created() {
        const happenings = this.user.getUpcommingHappeningsByTeam(this.team.id);
        this.happenings = happenings.slice(0, 5);
    },
    methods: {
        goToHappening(happening) {
            if (happening.surveyId) {
                this.$router.push({
                    name: "SurveyPage",
                    params: { id: happening.surveyId },
                });
            } else {
                this.$router.push({
                    name: "HappeningPage",
                    params: { id: happening.id },
                });
            }
        },
    },
};
</script>
