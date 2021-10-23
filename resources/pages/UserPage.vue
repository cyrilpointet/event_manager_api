<template>
    <div>
        <div v-if="isLogged">
            <Account />
        </div>
        <div v-else>
            <div class="logoContainer">
                <Logo />
            </div>

            <transition name="turn" mode="out-in">
                <v-card
                    v-if="hasAccount"
                    key="Login"
                    max-width="500"
                    class="mx-auto rounded-xl"
                >
                    <v-card-title>Me connecter</v-card-title>
                    <v-card-text>
                        <Login />
                    </v-card-text>
                </v-card>

                <v-card
                    v-if="!hasAccount"
                    key="Register"
                    max-width="500"
                    class="mx-auto rounded-xl"
                >
                    <v-card-title>Créer mon compte</v-card-title>
                    <v-card-text>
                        <Register />
                    </v-card-text>
                </v-card>
            </transition>

            <div class="d-flex justify-center pa-6">
                <v-btn
                    small
                    rounded
                    color="primary white--text"
                    @click="hasAccount = !hasAccount"
                >
                    {{
                        hasAccount
                            ? "Je n'ai pas de compte"
                            : "J'ai déjà un compte"
                    }}
                </v-btn>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import Login from "../components/user/Login";
import Register from "../components/user/Register";
import Account from "../components/user/Account";
import Logo from "../components/common/logo";

export default {
    name: "UserPage",
    components: { Login, Register, Account, Logo },
    data() {
        return {
            hasAccount: false,
        };
    },
    computed: {
        ...mapState({
            user: (state) => state.user.user,
        }),
        ...mapGetters({
            isLogged: "user/isLogged",
        }),
    },
    created() {
        this.$store.commit("setTitle", "Mon compte");
    },
};
</script>

<style lang="scss" scoped>
.logoContainer {
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 2rem;
    margin-top: 2rem;
}
</style>
