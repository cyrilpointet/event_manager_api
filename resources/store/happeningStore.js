import { ApiConsumer } from "../services/ApiConsumer";
import { Happening } from "../models/Happening";

export const happeningStore = {
    namespaced: true,

    state: () => ({
        happening: null,
    }),
    mutations: {
        setHappening(state, value) {
            state.happening = value;
        },
        removeHappening(state) {
            state.happening = null;
        },
    },
    actions: {
        getHappeningById(context, id) {
            return new Promise((resolve, reject) => {
                ApiConsumer.get("happening/" + id)
                    .then((happening) => {
                        const newHappening = new Happening(happening);
                        context.commit("setHappening", newHappening);
                        resolve(newHappening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        createHappening(context, values) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post(
                    `team/${values.teamId}/happening`,
                    values.happening
                )
                    .then((happening) => {
                        context.commit(
                            "setHappening",
                            new Happening(happening)
                        );
                        resolve(happening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        updateHappening(context, values) {
            return new Promise((resolve, reject) => {
                ApiConsumer.put(`happening/${values.id}`, values)
                    .then((happening) => {
                        context.commit(
                            "setHappening",
                            new Happening(happening)
                        );
                        resolve(happening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        deleteHappening(context) {
            return new Promise((resolve, reject) => {
                ApiConsumer.delete(
                    "happening/" + context.state.happening.id,
                    {}
                )
                    .then((resp) => {
                        context.commit("setHappening", null);
                        resolve(resp);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        addMemberToHappening(context, userId) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post(
                    `happening/${context.state.happening.id}/member`,
                    {
                        id: userId,
                    }
                )
                    .then((happening) => {
                        const newHappening = new Happening(happening);
                        context.commit("setHappening", newHappening);
                        resolve(newHappening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        removeMemberFromHappening(context, userId) {
            return new Promise((resolve, reject) => {
                ApiConsumer.delete(
                    `happening/${context.state.happening.id}/member`,
                    {
                        id: userId,
                    }
                )
                    .then((happening) => {
                        const newHappening = new Happening(happening);
                        context.commit("setHappening", newHappening);
                        resolve(newHappening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        updateMemberPresence(context, value) {
            return new Promise((resolve, reject) => {
                ApiConsumer.put(
                    `happening/${context.state.happening.id}/member`,
                    value
                )
                    .then((happening) => {
                        const newHappening = new Happening(happening);
                        context.commit("setHappening", newHappening);
                        resolve(newHappening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        addComment(context, content) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post(
                    `happening/${context.state.happening.id}/comment`,
                    {
                        text: content,
                    }
                )
                    .then((happening) => {
                        const newHappening = new Happening(happening);
                        context.commit("setHappening", newHappening);
                        resolve(newHappening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        deleteComment(context, id) {
            return new Promise((resolve, reject) => {
                ApiConsumer.delete(
                    `happening/${context.state.happening.id}/comment/${id}`
                )
                    .then((happening) => {
                        const newHappening = new Happening(happening);
                        context.commit("setHappening", newHappening);
                        resolve(newHappening);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
    },
};
