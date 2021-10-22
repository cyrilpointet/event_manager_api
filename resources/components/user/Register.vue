<template>
    <div>
        <v-form ref="form" v-model="valid">
            <v-text-field
                v-model="name"
                label="Nom"
                :rules="[rules.required]"
            />

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
                @click="register"
            >
                ok
            </v-btn>
        </v-form>
    </div>
</template>

<script>
import { formValidators } from "../../services/formValidators";

export default {
    name: "Register",
    data: () => {
        return {
            valid: true,
            name: "",
            email: "",
            password: "",
            ajaxPending: false,
            rules: formValidators,
        };
    },
    created() {
        this.$store.commit("setColor", "default");
    },
    methods: {
        async register() {
            this.ajaxPending = true;
            try {
                await this.$store.dispatch("user/createUser", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                });
                this.ajaxPending = false;
                this.$router.push({ name: "Home" });
            } catch (error) {
                this.ajaxPending = false;
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Cet email est déjà utilisé",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
        },
    },
};
</script>
