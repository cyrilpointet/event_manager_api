<template>
    <v-list dense>
        <template v-for="(team, index) in user.teams">
            <v-divider :key="'divider' + team.id" v-if="index !== 0" />
            <v-list-item :key="team.id" @click="goToTeamPage(team)">
                <v-list-item-content>
                    <v-list-item-title>{{ team.name }}</v-list-item-title>
                </v-list-item-content>
                <v-list-item-icon>
                    <v-icon> mdi-chevron-right </v-icon>
                </v-list-item-icon>
            </v-list-item>
        </template>
    </v-list>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "TeamViewer",
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
    },
    methods: {
        goToTeamPage(team) {
            this.$router.push({ name: "Team", params: { id: team.id } });
        },
    },
};
</script>
