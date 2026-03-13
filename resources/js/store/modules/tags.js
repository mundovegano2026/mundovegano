import globalAxios from 'axios'

const state = {
    tags: [],
    // mainCategories: [],
    currentTag: {
        name: '',
        productList: []
    }
};

const mutations = {
    // 'SET_CATEGORIES' (state, categories) {
    //     state.categories = categories;
    // },
    'SET_TAG' (state, tag) {
        state.currentTag = tag;
    },
    // 'SET_MAIN_CATEGORIES' (state, categories) {
    //     console.log("Setting");
    //     console.log(categories);
    //     state.mainCategories = categories;
    //}
};

const actions = {
    // initCategories: ({commit}) => {
    //     globalAxios.get('/api/categories')
    //     .then(res => {
    //         console.log(res.data.data);
    //         console.log("TESTE");
    //         commit('SET_CATEGORIES', res.data.data);
    //         commit('SET_MAIN_CATEGORIES', res.data.data.filter(c => c.level == 1));
    //         globalAxios.get('/api/categories/' + state.mainCategories[0].id)
    //         .then(res => {
    //             console.log("SETTING FIRST");
    //             console.log(res.data.data);
    //             if(state.currentCategory.name == "")
    //                 commit('SET_CATEGORY', res.data.data);
    //         })
    //         .catch(error => {
    //             console.log(error);
    //         });
    //     })
    //     .catch(error => {
    //         console.log(error);
    //     });
    // },
    fetchTag: ({commit}, id) => {
        console.log(id);
        return globalAxios.get('/api/tags/' + id)
        .then(res => {
            commit('SET_TAG', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    // fetchCategoryName: ({commit}, name) => {
    //     return globalAxios.get('/api/categoryByName/' + name)
    //     .then(res => {
    //         commit('SET_CATEGORY', res.data.data);
    //     })
    //     .catch(error => {
    //         console.log(error);
    //     });
    // },
    // updateCurrentCategory: ({commit}, category) => {
    //     console.log("UPDATING AGAIN");
    //     console.log(category);
    //     commit('SET_CATEGORY', category);
    // }
};

const getters = {
    // categories: state => {
    //     return state.categories;
    // },
    currentTag: state => {
        return state.currentTag;
    }
    // mainCategories: state => {
    //     return state.mainCategories;
    // }
};

export default {
    state,
    mutations,
    actions,
    getters
};