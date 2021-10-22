<template>
    <div class="pt-2">
        <div
            v-ripple
            class="teamLink d-flex hoverable"
            @click="
                $router.push({
                    name: 'Team',
                    params: { id: happening.team.id },
                })
            "
        >
            <span class="flex-grow-1">
                <h5>Groupe</h5>
                <p>{{ happening.team.name }}</p>
            </span>
            <v-icon> mdi-chevron-right </v-icon>
        </div>

        <h5>Lieu</h5>
        <p>{{ happening.place }}</p>
        <h5>Description</h5>
        <p>{{ happening.description }}</p>
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5>Début</h5>
                <p>{{ happening.startAt }}</p>
            </div>
            <div class="flex-grow-1">
                <h5>Fin</h5>
                <p>{{ happening.endAt }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "HappeningInfos",
    computed: {
        ...mapState({
            happening: (state) => state.happening.happening,
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
