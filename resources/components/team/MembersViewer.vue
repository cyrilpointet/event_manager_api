<template>
    <div>
        <template v-for="(member, index) in team.members">
            <v-divider :key="'divider' + member.id" v-if="index !== 0" />
            <v-list-item :key="member.id">
                <v-list-item-content>
                    <v-list-item-title>{{ member.name }}</v-list-item-title>
                </v-list-item-content>
                <v-list-item-icon v-if="member.isAdmin">
                    <v-icon>mdi-star</v-icon>
                </v-list-item-icon>
            </v-list-item>
        </template>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "MembersViewer",
    computed: {
        ...mapState({
            team: (state) => state.team.team,
        }),
    },
};
</script>
