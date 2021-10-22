<template>
    <div class="pt-2">
        <div
            v-ripple
            class="teamLink d-flex"
            @click="
                $router.push({
                    name: 'Team',
                    params: { id: survey.team.id },
                })
            "
        >
            <span class="flex-grow-1">
                <h5>Groupe</h5>
                <p>{{ survey.team.name }}</p>
            </span>
            <v-icon> mdi-chevron-right </v-icon>
        </div>

        <h5>Lieu</h5>
        <p>{{ survey.place }}</p>
        <h5>Description</h5>
        <p>{{ survey.description }}</p>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "SurveyInfos",
    computed: {
        ...mapState({
            survey: (state) => state.survey.survey,
        }),
    },
};
</script>

<style lang="scss" scoped>
.teamLink {
    cursor: pointer;
    transition: background-color linear 0.1s;
    &:hover {
        background-color: #eeeeee;
    }
}
</style>
