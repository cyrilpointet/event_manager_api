<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Nom</th>
                    <th class="text-left">Participation</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(member, index) in members" :key="member.id">
                    <td>{{ member.name }}</td>
                    <td>
                        <v-switch
                            v-model="member.status"
                            :disabled="member.id === user.id"
                            @change="handleStatusChange($event, index)"
                        />
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "MembersSelector",
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            members: (state) => state.form.members,
        }),
    },
    methods: {
        handleStatusChange(newStatus, index) {
            this.$store.commit("form/updateMemberStatus", {
                index: index,
                status: newStatus,
            });
        },
    },
};
</script>
