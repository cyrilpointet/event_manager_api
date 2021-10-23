<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Nom</th>
                    <th class="text-left">Présence</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="member in happening.members" :key="member.id">
                    <td>{{ member.name }}</td>
                    <td
                        :class="getColor(member.presence)"
                        class="d-flex justify-center align-center"
                        v-if="user.id === member.id"
                    >
                        <v-menu offset-y :disabled="ajaxPending">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on" rounded>
                                    <v-icon :color="getColor(member.presence)">
                                        {{ getIcon(member.presence) }}
                                    </v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item
                                    v-for="(item, index) in items"
                                    :key="index"
                                    @click="handleChange(item.value)"
                                >
                                    <v-icon :color="item.color">
                                        {{ item.icon }}
                                    </v-icon>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </td>

                    <td
                        v-else
                        class="pa-0 d-flex align-center justify-center"
                        :class="getColor(member.presence)"
                    >
                        <v-icon color="white">
                            {{ getIcon(member.presence) }}
                        </v-icon>
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { mapState } from "vuex";
import { PRESENCE_STATUS } from "../../constantes/PresencesStatus";

export default {
    name: "MembersPresences",
    data: () => ({
        presenceStatus: PRESENCE_STATUS,
        ajaxPending: false,
        items: [
            { icon: "mdi-account-check", color: "success", value: "yes" },
            { icon: "mdi-account-question", color: "warning", value: "maybe" },
            { icon: "mdi-account-remove", color: "error", value: "no" },
        ],
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            happening: (state) => state.happening.happening,
        }),
    },
    methods: {
        getIcon(presence) {
            return PRESENCE_STATUS[presence].icon;
        },
        getColor(presence) {
            return PRESENCE_STATUS[presence].color;
        },
        async handleChange(newStatus) {
            try {
                await this.$store.dispatch("happening/updateMemberPresence", {
                    presence: newStatus,
                });
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Présence mise à jour",
                    },
                });
                document.dispatchEvent(event);
            } catch {
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Oups... Une erreur est survenue !",
                        color: "error",
                    },
                });
                document.dispatchEvent(event);
            }
        },
    },
};
</script>
