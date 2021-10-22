<template>
    <v-list nav dense>
        <template v-if="isLogged">
            <v-list-item @click="$router.push({ name: 'Home' })">
                <v-list-item-icon>
                    <v-icon>mdi-home</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Accueil</v-list-item-title>
            </v-list-item>

            <v-list-item @click="$router.push({ name: 'UserHappeningsPage' })">
                <v-list-item-icon>
                    <v-icon>mdi-calendar-text</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Mes évènements</v-list-item-title>
            </v-list-item>

            <v-list-item @click="$router.push({ name: 'Account' })">
                <v-list-item-icon>
                    <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Mon compte</v-list-item-title>
            </v-list-item>

            <template v-if="user.teams.length > 0">
                <v-subheader>Mes groupes</v-subheader>

                <v-list-item
                    v-for="team in user.teams"
                    :key="team.id"
                    @click="
                        $router.push({ name: 'Team', params: { id: team.id } })
                    "
                >
                    <v-list-item-icon>
                        <v-icon>mdi-account-multiple</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>{{ team.name }}</v-list-item-title>
                </v-list-item>
            </template>
        </template>

        <template v-else>
            <v-list-item>
                <v-list-item-icon>
                    <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Connexion</v-list-item-title>
            </v-list-item>
        </template>
    </v-list>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
    name: "NavMenu",
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
        }),
    },
};
</script>
