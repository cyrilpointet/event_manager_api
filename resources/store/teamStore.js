import { ApiConsumer } from "../services/ApiConsumer";
import { Team } from "../models/Team";

export const teamStore = {
    namespaced: true,

    state: () => ({
        team: null,
    }),
    getters: {
        isUserAdmin: (state, getters, rootState) => {
            if (!state.team) {
                return false;
            }
            const userId = rootState.user.user.id;
            const member = state.team.members.find(
                (elem) => elem.id === userId
            );
            return member && member.isAdmin;
        },
    },
    mutations: {
        setTeam(state, value) {
            state.team = value;
        },
        removeTeam(state) {
            state.team = null;
        },
    },
    actions: {
        getTeamById(context, teamId) {
            return new Promise((resolve, reject) => {
                ApiConsumer.get("team/" + teamId)
                    .then((team) => {
                        const newTeam = new Team(team);
                        context.commit("setTeam", newTeam);
                        resolve(team);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },

        createTeam(context, values) {
            return new Promise((resolve, reject) => {
                ApiConsumer.post("team", values)
                    .then((team) => {
                        const newTeam = new Team(team);
                        context.commit("setTeam", newTeam);
                        resolve(team);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },

        updateTeam(context, values) {
            return new Promise((resolve, reject) => {
                ApiConsumer.put("team/" + values.id, { name: values.name })
                    .then((team) => {
                        const newTeam = new Team(team);
                        context.commit("setTeam", newTeam);
                        resolve(team);
                    })
                    .catch((e) => {
                        reject(e);
                    });
            });
        },
    },
};
