export const state = {
    delete_url: ''
};

export const actions = {
    ['set_delete_data'](context, val) {
        context.commit('updateDeleteData', val);
    }
};

export const mutations = {
    updateDeleteData: (state, val) => {
        state.delete_url = val.url
    },
};

export const getters = {
    ['delete_data'](state) {
        return state
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};
