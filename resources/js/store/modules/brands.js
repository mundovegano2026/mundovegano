import globalAxios from 'axios'

const state = {
    currentBrand: {
        productList: []
    }
};

const mutations = {
    'SET_BRAND' (state, brand) {
        state.currentBrand = brand;
    }
};

const actions = {
    fetchBrandName: ({commit}, name) => {
        return globalAxios.get('/api/brandByName/' + name)
        .then(res => {
            commit('SET_BRAND', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    updateCurrentBrand: ({commit}, brand) => {
        commit('SET_BRAND', brand);
    }
};

const getters = {
    currentBrand: state => {
        return state.currentBrand;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
};