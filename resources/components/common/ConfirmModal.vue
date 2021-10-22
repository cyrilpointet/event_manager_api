<template>
    <v-dialog v-model="isOpen" width="500">
        <v-card class="rounded-xl">
            <v-card-title class="default black--text">
                {{ title }}
            </v-card-title>

            <v-card-text v-if="text" class="pt-4">
                <p class="black--text text-body-1">{{ text }}</p>
            </v-card-text>

            <v-card-actions>
                <v-btn @click="isOpen = false" rounded>Annuler</v-btn>
                <v-spacer />
                <v-btn color="error" @click="doAction" rounded>Confirmer</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "ConfirmModal",
    data: () => {
        return {
            isOpen: false,
            title: "",
            text: "",
            callback: null,
        };
    },
    mounted() {
        document.addEventListener("confirmAction", (evt) => {
            this.text = evt.detail.text;
            this.title = evt.detail.title;
            this.callback = evt.detail.callback;
            this.isOpen = true;
        });
    },
    methods: {
        doAction() {
            this.isOpen = false;
            if (this.callback !== null) {
                this.callback();
            }
        },
    },
};
</script>
