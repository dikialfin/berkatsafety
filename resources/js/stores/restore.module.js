export const state = {
    restore_url: ''
};

export const actions = {
    ['set_restore_data'](context, val) {
        context.commit('updateRestoreData', val);
    }
};

export const mutations = {
    updateRestoreData: (state, val) => {
        state.restore_url = val.url
    },
};

export const getters = {
    ['restore_data'](state) {
        return state
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};
