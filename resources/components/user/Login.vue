<template>
    <div>
        <v-form ref="form" v-model="valid">
            <v-text-field
                v-model="email"
                label="Email"
                :rules="[rules.required, rules.email]"
            />

            <v-text-field
                v-model="password"
                label="Mot de passe"
                type="password"
                :rules="[rules.required]"
            />

            <v-btn
                color="secondary"
                min-width="100%"
                rounded
                :disabled="!valid"
                :loading="ajaxPending"
                @click="logUser"
            >
                ok
            </v-btn>
        </v-form>
    </div>
</template>

<script>
import { formValidators } from "../../services/formValidators";

export default {
    name: "Login",
    data() {
        return {
            valid: true,
            email: "",
            password: "",
            rules: formValidators,
            ajaxPending: false,
        };
    },
    created() {
        this.$store.commit("setColor", "default");
    },
    methods: {
        async logUser() {
            this.ajaxPending = true;
            try {
                await this.$store.dispatch("user/logUser", {
                    email: this.email,
                    password: this.password,
                });
                this.ajaxPending = false;
                if (this.$route.name !== "home") {
                    this.$router.push({ name: "Home" });
                }
            } catch (error) {
                this.ajaxPending = false;
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Mauvais identifiant / Mot de passe",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
        },
    },
};
</script>
