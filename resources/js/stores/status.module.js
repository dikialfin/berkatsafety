export const state = {
    status_url: ''
};

export const actions = {
    ['set_status_data'](context, val) {
        context.commit('updatestatusData', val);
    }
};

export const mutations = {
    updatestatusData: (state, val) => {
        state.status_url = val.url
    },
};

export const getters = {
    ['status_data'](state) {
        return state
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};
