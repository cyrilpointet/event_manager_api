<template>
    <div class="mainComponent">
        <table>
            <thead>
                <th>Début</th>
                <th
                    class="pa-2"
                    v-for="happening in survey.happenings"
                    :key="happening.id"
                    style="white-space: nowrap"
                >
                    {{ happening.startAtDay }}<br />
                    {{ happening.startAtHour }}
                </th>
            </thead>
            <thead>
                <th>Fin</th>
                <th
                    style="white-space: pre-line"
                    class="pa-2"
                    v-for="happening in survey.happenings"
                    :key="happening.id"
                >
                    {{ happening.endAt }}
                </th>
            </thead>
            <tbody>
                <tr v-for="(member, mi) in survey.members" :key="member.id">
                    <td class="pa-2">{{ member.name }}</td>
                    <template v-for="(happening, hi) in survey.happenings">
                        <td
                            v-if="user.id !== happening.members[mi].id"
                            :key="hi"
                            class="pa-0"
                            :class="getColor(happening.members[mi].presence)"
                        >
                            <span class="d-flex justify-center align-center">
                                <v-icon>
                                    {{
                                        getIcon(happening.members[mi].presence)
                                    }}
                                </v-icon>
                            </span>
                        </td>
                        <td
                            v-else
                            :key="hi"
                            class="pa-0"
                            :class="getColor(happening.members[mi].presence)"
                        >
                            <span class="d-flex justify-center">
                                <v-menu offset-y :disabled="ajaxPending">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn v-bind="attrs" v-on="on">
                                            <v-icon
                                                :color="
                                                    getColor(
                                                        happening.members[mi]
                                                            .presence
                                                    )
                                                "
                                            >
                                                {{
                                                    getIcon(
                                                        happening.members[mi]
                                                            .presence
                                                    )
                                                }}
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item
                                            v-for="(item, index) in items"
                                            :key="index"
                                            @click="
                                                editPresence(
                                                    happening.id,
                                                    item.value
                                                )
                                            "
                                        >
                                            <v-icon :color="item.color">{{
                                                item.icon
                                            }}</v-icon>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </span>
                        </td>
                    </template>
                </tr>
                <SurveyValidator v-if="isUserAdmin" />
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import { PRESENCE_STATUS } from "../../constantes/PresencesStatus";
import SurveyValidator from "./SurveyValidator";

export default {
    name: "SurveyPresences",
    components: { SurveyValidator },
    data: () => ({
        items: [
            { icon: "mdi-account-check", color: "success", value: "yes" },
            { icon: "mdi-account-question", color: "warning", value: "maybe" },
            { icon: "mdi-account-remove", color: "error", value: "no" },
        ],
        ajaxPending: false,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            survey: (state) => state.survey.survey,
        }),
        ...mapGetters({
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    methods: {
        getIcon(presence) {
            return PRESENCE_STATUS[presence].icon;
        },
        getColor(presence) {
            return PRESENCE_STATUS[presence].color;
        },
        async editPresence(happeningId, value) {
            this.ajaxPending = true;
            try {
                await this.$store.dispatch("survey/updateMemberPresence", {
                    happeningId: happeningId,
                    presence: value,
                });
                this.ajaxPending = false;
                const event = new CustomEvent("displayMsg", {
                    detail: {
                        text: "Présence mise à jour",
                    },
                });
                document.dispatchEvent(event);
            } catch {
                this.ajaxPending = false;
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

<style lang="scss" scoped>
.mainComponent {
    max-width: 100%;
    overflow-x: auto;
}
table {
    border-collapse: collapse;
}
td,
th {
    border: 1px solid black;
}
.isEditable {
    background-color: lightgrey;
}
</style>
