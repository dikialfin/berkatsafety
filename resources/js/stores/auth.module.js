import Cookies from 'js-cookie'

export const state = {
    user: null,
    token: Cookies.get('token'),
    department: Cookies.get('department'),
    membership: Cookies.get('membership')
};

export const actions = {
    ['set_user'](context, user) {
        context.commit('updateUser', user);
    },
    ['set_dept'](context, dept) {
        context.commit('updateDept', dept);
    },
    ['set_membership'](context, membership) {
        context.commit('updateMembership', membership);
    },
    ['set_token'](context, token) {
        context.commit('updateToken', token);
        Cookies.set('token', token, { expires: null })
    },
    ['attempt_user'](context) {
      return axios.get('/api/v1/me')
        .then((response) => {
            context.commit('updateUser', response.data)
            return response
        })
        .catch((error) => {
            context.commit('updateUser', null);
            throw error
        })
    },
    ['logout'](context) {
      return axios.post('/api/v1/logout')
        .then((response) => {
            return context.commit('logoutUser');
        })
        .catch((error) => {
            console.error(error)
            throw error
        })
    }
};

export const mutations = {
    updateUser: (state, user) => {
        state.user = user
        if(user != null){
            if(!Cookies.get('department')){
                let dept = 1;
                if(user.role.department_id != null){
                    state.department = user.role.department_id
                    dept = user.role.department_id;
                }
                Cookies.set('department', dept, { expires: null })
            }
        }
    },
    updateDept: (state,dept) => {
        state.department = dept
        Cookies.set('department', dept, { expires: null })
    },
    updateMembership: (state,membership) => {
        state.membership = membership
        Cookies.set('membership', membership, { expires: null })
    },
    updateToken: (state, token) => {
        state.token = token
    },
    logoutUser: (state) => {
        if(state.user != null){
            state.user = null
            state.token = null
            state.department = 1
            state.membership = null
            Cookies.remove('token')
            Cookies.remove('department')
            Cookies.remove('membership')
        }
    }
};

export const getters = {
    ['user'](state) {
        return state.user
    },
    ['department'](state) {
        return state.department
    },
    ['community'](state) {
        return state.community
    },
    ['token'](state) {
        return state.token
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};
