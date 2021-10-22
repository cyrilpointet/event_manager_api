import { ApiConsumer } from "../services/ApiConsumer";
import { Survey } from "../models/Survey";

export const surveyStore = {
    namespaced: true,

    state: () => ({
        survey: null,
    }),
    mutations: {
        setSurvey(state, value) {
            state.survey = value;
        },
        removeSurvey(state) {
            state.survey = null;
        },
    },
    actions: {
        createSurvey(context, values) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post(`team/${values.teamId}/survey`, values.survey)
                    .then((survey) => {
                        context.commit("setSurvey", new Survey(survey));
                        resolve(survey);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        getSurveyById(context, id) {
            return new Promise((resolve, reject) => {
                ApiConsumer.get("survey/" + id)
                    .then((survey) => {
                        const newSurvey = new Survey(survey);
                        context.commit("setSurvey", newSurvey);
                        resolve(newSurvey);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        updateMemberPresence(context, value) {
            return new Promise((resolve, reject) => {
                ApiConsumer.put(`happening/${value.happeningId}/member`, {
                    presence: value.presence,
                })
                    .then(async () => {
                        await context.dispatch(
                            "getSurveyById",
                            context.state.survey.id
                        );
                        resolve();
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        addMemberToSurvey(context, userId) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post(`survey/${context.state.survey.id}/member`, {
                    id: userId,
                })
                    .then((survey) => {
                        const newSurvey = new Survey(survey);
                        context.commit("setSurvey", newSurvey);
                        resolve(newSurvey);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        removeMemberFromSurvey(context, userId) {
            return new Promise((resolve, reject) => {
                ApiConsumer.delete(`survey/${context.state.survey.id}/member`, {
                    id: userId,
                })
                    .then((survey) => {
                        const newSurvey = new Survey(survey);
                        context.commit("setSurvey", newSurvey);
                        resolve(newSurvey);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        validateSurvey(context, ids) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post(`survey/${context.state.survey.id}/validate`, {
                    happenings: ids,
                })
                    .then(() => {
                        context.commit("removeSurvey");
                        resolve();
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
        updateSurvey(context, values) {
            return new Promise((resolve, reject) => {
                ApiConsumer.put(`survey/${values.id}`, values)
                    .then((survey) => {
                        context.commit("setSurvey", new Survey(survey));
                        resolve(survey);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
    },
};
