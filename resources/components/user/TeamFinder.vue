<template>
    <div>
        <v-form ref="form">
            <v-text-field
                v-model="name"
                label="Nom"
                append-outer-icon="mdi-send"
                color="secondary"
                @click:append-outer="findteams"
            />
        </v-form>
        <div v-if="teams !== null">
            <template v-for="(team, index) in teams">
                <v-divider :key="'divider' + team.id" v-if="index !== 0" />
                <v-list-item :key="team.id">
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ team.name }}
                        </v-list-item-title>
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-btn icon @click="joinTeam(team)" color="secondary">
                            <v-icon> mdi-account-multiple-plus </v-icon>
                        </v-btn>
                    </v-list-item-action>
                </v-list-item>
            </template>
            <v-list-item v-if="teams.length < 1">
                <v-list-item-content>
                    <v-list-item-title>
                        Aucun résulat pour <strong>{{ name }}</strong>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import { ApiConsumer } from "../../services/ApiConsumer";

export default {
    name: "TeamFinder",
    data: () => {
        return {
            valid: true,
            name: "",
            ajaxPending: false,
            teams: null,
        };
    },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
    },
    watch: {
        name() {
            this.teams = null;
        },
    },
    methods: {
        async findteams() {
            if (this.name !== "") {
                this.teams = await ApiConsumer.get("team/name/" + this.name);
            }
        },
        async joinTeam(team) {
            console.log(team);
            try {
                await ApiConsumer.post(`user/invitation`, {
                    id: team.id,
                });
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "invitation envoyée",
                        timeout: 3000,
                    },
                });
                document.dispatchEvent(event);
            } catch (e) {
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: e.response.data.message,
                        color: "error",
                        timeout: 3000,
                    },
                });
                document.dispatchEvent(event);
            }
        },
    },
};
</script>
