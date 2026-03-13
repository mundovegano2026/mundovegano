import globalAxios from 'axios'

const state = {
    currentProductList: [],
    accounting: ""
};

const mutations = {
    'SET_LIST' (state, productList) {
        state.currentProductList = productList;
    },
    'SET_ACCOUNTING' (state, accounting) {
        state.accounting = accounting;
    }
};

const actions = {
    fetchProductList: ({commit}, options) => {
        // return globalAxios.get('/api/productList/' + options.searchText + "/" + options.order + "/" + options.filter.distrito + "/" + options.filter.concelho + "/" + options.filter.brand + "/" + options.filter.store)
        let path = '/api/productList';
        console.log("options");
        console.log(options);
        if(typeof options.user == "undefined" || !options.user) {
            path = '/api/guestProductList';
        }
        return globalAxios.post(path, options)
        .then(res => {
            console.log("We're Setting!");
            if(typeof res.data.productList != "undefined") {
                commit('SET_LIST', res.data.productList);
            }
        })
        .catch(error => {
            console.log(error);
        });
    },
    updateProductList: ({commit}, productList) => {
        commit('SET_LIST', productList);
    },
    fetchAccounting: ({commit},) => {
        let path = '/api/valuelist/despesas';
        console.log("options");

        return globalAxios.get(path)
        .then(res => {
            if(typeof res.data.value != "undefined") {
                commit('SET_ACCOUNTING', res.data.value);
            }
        })
        .catch(error => {
            console.log(error);
        });
    }
};

const getters = {
    currentProductList: state => {
        return state.currentProductList;
    },
    accounting: state => {
        return state.accounting;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
};