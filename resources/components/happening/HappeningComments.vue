<template>
    <div>
        <template v-for="(comment, index) in happening.comments">
            <v-divider :key="'divider' + comment.id" v-if="index !== 0" />
            <v-list-item :key="comment.id">
                <v-list-item-content>
                    <v-list-item-subtitle class="mb-2">
                        {{ comment.user.name }} - {{ comment.createdAt }}
                    </v-list-item-subtitle>
                    <p>{{ comment.content }}</p>
                </v-list-item-content>
                <v-list-item-action
                    v-if="isUserAdmin || user.id === comment.user.id"
                    @click="deleteComment(comment.id)"
                >
                    <v-btn icon>
                        <v-icon color="error">mdi-delete</v-icon>
                    </v-btn>
                </v-list-item-action>
            </v-list-item>
        </template>
        <v-divider />
        <v-list-item>
            <v-list-item-content>
                <v-textarea
                    v-model="newComment"
                    label="Nouveau commentaire"
                    rows="1"
                    auto-grow
                />
                <v-btn
                    color="secondary"
                    outlined
                    rounded
                    :disabled="!newComment || ajaxPending"
                    @click="addComment"
                >
                    Ajouter
                </v-btn>
            </v-list-item-content>
        </v-list-item>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
    name: "HappeningComments",
    data: () => ({
        newComment: null,
        ajaxPending: false,
    }),
    computed: {
        ...mapState({
            user: (state) => state.user.user,
            happening: (state) => state.happening.happening,
        }),
        ...mapGetters({
            isUserAdmin: "team/isUserAdmin",
        }),
    },
    methods: {
        async addComment() {
            this.ajaxPending = true;
            try {
                await this.$store.dispatch(
                    "happening/addComment",
                    this.newComment
                );
                this.newComment = null;
                this.ajaxPending = false;
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
        async deleteComment(id) {
            try {
                await this.$store.dispatch("happening/deleteComment", id);
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
