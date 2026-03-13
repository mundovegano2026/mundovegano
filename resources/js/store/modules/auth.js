import globalAxios from 'axios'

const state = {
    user: null
};

const mutations = {
    setUserData (state, userData) {
      state.user = userData.user
      localStorage.setItem('user', JSON.stringify(userData))
      axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
    },
    clearUserData (state) {
      localStorage.removeItem('user')
      //location.reload(redirectPage);
      window.location.assign('/');
    },
    setUserLocation (state, location) {
      state.user.distrito = location.distrito;
      state.user.concelho = location.concelho;
      var userData = JSON.parse(localStorage.getItem('user'));
      userData.user.distrito = location.distrito;
      userData.user.concelho = location.concelho;
      localStorage.setItem('user', JSON.stringify(userData));
    }
};

const actions = {
  login ({ commit }, credentials) {
    return axios
      .post('/api/login', credentials)
      .then(({ data }) => {
        commit('setUserData', data)
      })
  },  
  recover ({ commit }, credentials) {
    return axios
      .post('/api/recover', credentials)
      .then(({ data }) => {
        // commit('setUserData', data)
      })
  },  
  setnewpwd ({ commit }, credentials) {
    return axios
      .post('/api/setnewpwd', credentials)
      .then(({ data }) => {
        // commit('setUserData', data)
      })
  },
  register ({ commit }, credentials) {
    return axios
      .post('/api/register', credentials)
      .then(({ data }) => {
        commit('setUserData', data)
      })
  },
  updatePWD ({ commit }, credentials) {
    return axios
      .post('/api/updatepwd', credentials)
      .then(({ data }) => {
        commit('setUserData', data)
      })
  },  
  deleteAccount ({ commit }, credentials) {
    return axios
      .post('/api/deleteaccount', credentials)
      .then(({ data }) => {
        commit('clearUserData');
      })
  },  
  logout ({ commit }) {
    commit('clearUserData')
  },
  updateUserLocation ({ commit }, location) {
    commit('setUserLocation', location);
  }
};

const getters = {
    isLogged: state => {
        return !!state.user;
    },
    user: state => {
        return state.user;
    },
};

export default {
    state,
    mutations,
    actions,
    getters
};