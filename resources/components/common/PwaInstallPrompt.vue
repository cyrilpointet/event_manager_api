<template>
    <v-dialog v-model="deferredPrompt" persistent width="500">
        <v-card class="rounded-xl">
            <v-card-title class="default black--text">
                Installer l'application ?
            </v-card-title>
            <v-card-text class="pt-4">
                <p class="black--text text-body-1">
                    Souhaitez-vous installer l'application TEVMA sur votre
                    appareil ? (Recommandé)
                </p>
            </v-card-text>

            <v-card-actions>
                <v-btn
                    @click="$store.commit('setDeferredPrompt', null)"
                    rounded
                >
                    plus tard
                </v-btn>
                <v-spacer />
                <v-btn color="primary" @click="installApp" rounded
                    >Installer</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "PwaInstallPrompt",
    computed: {
        ...mapState({
            deferredPrompt: (state) => state.deferredPrompt,
        }),
    },
    methods: {
        installApp() {
            this.deferredPrompt.prompt();
            this.$store.commit("setDeferredPrompt", null);
        },
    },
};
</script>
