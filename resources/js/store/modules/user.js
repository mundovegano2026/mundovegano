import globalAxios from 'axios'

const state = {
    userDistrito: null,
    userConcelho: null
};

const mutations = {
    setLocation (state, userLocation) {
        state.userDistrito = userLocation.distrito;
        state.userConcelho = userLocation.concelho;
    }
};

const actions = {
  updateLocation ({ commit }, credentials) {
    return axios
      .post('/api/updatelocation', credentials)
      .then(({ data }) => {
        commit('setLocation', data)
      })
  }
};

const getters = {
    userDistrito: state => {
        return state.userDistrito;
    },
    userConcelho: state => {
        return state.userConcelho;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
};