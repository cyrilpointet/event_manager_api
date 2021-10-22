<template>
    <v-snackbar top v-model="isOpen" :timeout="timeout" :color="color">
        {{ text }}
    </v-snackbar>
</template>

<script>
export default {
    name: "MsgDisplayer",
    data() {
        return {
            isOpen: false,
            text: "",
            color: "",
            timeout: 2000,
        };
    },
    mounted() {
        document.addEventListener("displayMsg", (evt) => {
            this.text = evt.detail.text;
            this.color = evt.detail.color ? evt.detail.color : "primary";
            this.timeout = evt.detail.timeout ? evt.detail.timeout : 2000;
            this.isOpen = true;
        });
    },
};
</script>
