export const formStore = {
    namespaced: true,

    state: () => ({
        dates: [],
        members: [],
    }),
    getters: {
        selectedMembersIds: (state) => {
            const members = [];
            state.members.forEach((member) => {
                if (member.status === true) {
                    members.push(member.id);
                }
            });
            return members;
        },
    },
    mutations: {
        addDate(state, value) {
            state.dates.push({
                start: value.start,
                end: value.end,
            });
        },
        removeDate(state, index) {
            state.dates.splice(index, 1);
        },
        resetDates(state) {
            state.dates = [];
        },
        addMember(state, member) {
            state.members.push(member);
        },
        updateMemberStatus(state, values) {
            state.members[values.index].status = values.status;
        },
        resetMembers(state) {
            state.members = [];
        },
    },
};
