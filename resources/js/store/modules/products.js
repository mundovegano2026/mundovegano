import globalAxios from 'axios'
import utils from '../../utils'

const productModel = {
    name: '',
    image: '',
    image: [],
    savedImage: '',
    categoryCascade: [],
    obs: '',
    tags: '',
    created_at: '',
    brand: {
        name: ''
    },
    created_at: '',
    commentCount: 0,
    user: {
        name: ''
    },
    admin: {
        name: ''
    },
    rating: 0,
    comment: ''
    // category: {
    //     path: ''
    // }
}

const state = {
    productRegistered: false,
    isRegistering: false,
    registerMessage: '',
    newProducts: [],
    currentProduct: JSON.parse(JSON.stringify(productModel)),
    currentReview: null,
    isReviewed: false,
    reviewSuccess: false,
    reviewMessage: '',
    tags: [],
    reportTypes: [],
    freguesias: [],
    concelhos: [],
    distritos: [],
    productModel
};

const mutations = {
    'SET_CURRENT_STORES' (state, stores) {
        state.currentProduct.stores = stores;
    },
    'SET_REGISTERED' (state) {
        state.productRegistered = true;
    },
    'SET_REGISTERING' (state) {
        state.isRegistering = true;
    },
    'UNSET_REGISTERING' (state) {
        state.isRegistering = false;
    },
    'SET_PRODUCT' (state, product) {
        state.currentProduct = product;
        state.productRegistered = false;
    },
    'SET_NEW_PRODUCTS' (state, products) {
        state.newProducts = products;
    },
    'REGISTER_PRODUCT' (state, product) {
        state.currentProduct = product;
        state.productRegistered = true;
    },
    'SET_REVIEW' (state, review) {
        state.currentReview = review;
    },
    'RESET_REVIEW' (state) {
        state.currentReview = null;
    },
    'RESET_PRODUCT' (state) {
        state.currentProduct = JSON.parse(JSON.stringify(productModel));
    },
    'SET_REVIEW_MESSAGE' (state, message) {
        state.reviewMessage = message;
    },
    'SET_REVIEW_SUCCESS' (state) {
        state.reviewSuccess = true;
    },
    'SET_REVIEWED' (state) {
        state.isReviewed = true;
    },
    'SET_TAGS' (state, tags) {
        state.tags = tags;
    },
    'SET_ERROR' (state, error) {
        state.registerMessage = error;
        utils.addVueFlash("danger", ".subpage", error);
    },
    'SET_REGISTERED' (state) {
        state.productRegistered = true;
    },
    'SET_FREGUESIAS' (state, freguesias) {
        state.freguesias = freguesias;
    },
    'SET_REPORT_TYPES' (state, reportTypes) {
        state.reportTypes = reportTypes;
    },
    'SET_CONCELHOS' (state, concelhos) {
        state.concelhos = concelhos;
    },
    'SET_CONCELHOS_WITH_DEFAULT' (state, concelhoData) {
        state.concelhos = concelhoData.concelhos;
    },
    'SET_FREGUESIAS_WITH_DEFAULT' (state, freguesiaData) {
        state.freguesias = freguesiaData.freguesias;
    },
    'SET_DISTRITOS' (state, distritos) {
        state.distritos = distritos;
    },
    'REGISTER_PRODUCT_STORE' (state, store) {
        state.currentProduct.stores.push(store);
    }
};

const actions = {
    reviewProduct: ({commit}, data) => {
        return globalAxios.post('/api/review', data)
        .then(res => {
            commit('SET_REVIEW', res.data);
            commit('SET_REVIEW_SUCCESS');
            commit('SET_REVIEWED');
            commit('SET_REVIEW_MESSAGE', "Obrigado pela sua contribuição.");
        })
        .catch(error => {
            if(error.response) {

                if(error.response.status == 401) {
                    // Unauthorized error
                    commit('SET_REVIEW_MESSAGE', "Sem permissões.");
                }
                else if(error.response.status == 403) {
                    // Error duplicate
                    console.log("FORBIDDEN");
                    commit('SET_REVIEWED');
                    commit('SET_REVIEW_MESSAGE', "Artigo já foi avaliado.");
                }
                else if(error.response.status == 404) {
                    // Error Saving
                }
            }
        });
    },
    reviewGuest: ({commit}, data) => {
        return globalAxios.post('/api/guest-review', data)
        .then(res => {
            commit('SET_REVIEW', res.data);
            commit('SET_REVIEW_SUCCESS');
            commit('SET_REVIEWED');
            commit('SET_REVIEW_MESSAGE', "Obrigado pela sua contribuição.");
        })
        .catch(error => {
            if(error.response) {

                if(error.response.status == 401) {
                    // Unauthorized error
                    commit('SET_REVIEW_MESSAGE', "Sem permissões.");
                }
                else if(error.response.status == 403) {
                    // Error duplicate
                    commit('SET_REVIEWED');
                    commit('SET_REVIEW_MESSAGE', "Artigo já foi avaliado.");
                }
                else if(error.response.status == 404) {
                    // Error Saving
                }
            }
            console.log("TESTE ERRO");
        });
    },
    resetReview: ({commit}) => {
        commit('RESET_REVIEW');
    },
    resetProduct: ({commit}) => {
        commit('RESET_PRODUCT');
    },
    fetchProduct: ({commit}, id) => {
        globalAxios.get('/api/products/' + id)
        .then(res => {
            commit('SET_PRODUCT', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchProductByName: ({commit}, name) => {
        globalAxios.get('/api/productByName/' + name)
        .then(res => {
            commit('SET_PRODUCT', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchProductCoord: ({commit}, data) => {
        globalAxios.get('/api/products/' + data.id + '/' + data.pos.latitude + '/' + data.pos.longitude)
        .then(res => {
            commit('SET_PRODUCT', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchProductByNameCoord: ({commit}, data) => {
        globalAxios.get('/api/productByName/' + data.name + '/' + data.pos.latitude + '/' + data.pos.longitude)
        .then(res => {
            commit('SET_PRODUCT', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchTags: ({commit}) => {
        globalAxios.get('/api/tags/')
        .then(res => {
            commit('SET_TAGS', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    setNewProduct: ({commit}, product) => {
        commit('REGISTER_PRODUCT', product);
    },
    setNewProductStore: ({commit}, store) => {
        commit('REGISTER_PRODUCT_STORE', store);
    },
    submitProduct: ({commit}, product, image) => {

        const formData = new FormData();
        formData.append('product', JSON.stringify(product));
        formData.append('image', image.value);
        return globalAxios({
            method: 'post',
            url: '/api/products',
            data: formData,
            headers: {'content-type': 'multipart/form-data' }
            })
        .then(res => {

            commit('REGISTER_PRODUCT', res.data.product);
            commit('SET_REGISTERED');
            commit('SET_ERROR', "");
        })
        .catch(error => {
            if(error.response) {
                if(error.response.status == 422) {
                    commit('SET_ERROR', "Dados inválidos.");
                } else {
                    commit('SET_ERROR', "Erro a registar produto.");
                }
            } else {
                commit('SET_ERROR', "Erro a registar produto.");
            }

        });
    },
    resetProduct: ({commit}) => {
        commit('SET_PRODUCT', JSON.parse(JSON.stringify(productModel)));
    },
    setRegistered: ({commit}) => {
        commit('SET_REGISTERED');
    },
    fetchReview: ({commit}, product) => {


        // return globalAxios.get('/api/product-review/' + product)
        return globalAxios.get('/api/product-review/', product)
        .then(res => {
            commit('SET_REVIEW', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchReviewByName: ({commit}, productName) => {
        return globalAxios.get('/api/product-review-name/' + productName)
        .then(res => {
            commit('SET_REVIEW', res.data.data);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchReportTypes: ({commit}) => {
        return globalAxios.get('/api/reportTypes/')
        .then(res => {
            commit('SET_REPORT_TYPES', res.data.reportTypes);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchFreguesias: ({commit}, concelho) => {
        return globalAxios.get('/api/caop/freguesias/' + concelho)
        .then(res => {
            commit('SET_FREGUESIAS', res.data.freguesias);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchConcelhos: ({commit}, distrito) => {
        return globalAxios.get('/api/caop/concelhos/' + distrito)
        .then(res => {
            commit('SET_CONCELHOS', res.data.concelhos);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchSetConcelhos: ({commit}, searchData) => {
        return globalAxios.get('/api/caop/concelhos/' + searchData.distrito)
        .then(res => {
            let setData = {"concelhos": res.data.concelhos, "concelho": searchData.concelho};
            commit('SET_CONCELHOS_WITH_DEFAULT', setData);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchSetFreguesias: ({commit}, searchData) => {
        return globalAxios.get('/api/caop/freguesias/' + searchData.concelho)
        .then(res => {
            console.log("Freguesias fetched");
            console.log(res.data);
            let setData = {"freguesias": res.data.freguesias, "freguesia": searchData.freguesia};
            commit('SET_FREGUESIAS_WITH_DEFAULT', setData);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchDistritos: ({commit}) => {
        return globalAxios.get('/api/caop/distritos')
        .then(res => {
            commit('SET_DISTRITOS', res.data.distritos);
        })
        .catch(error => {
            console.log(error);
        });
    },
    initNewProducts: ({commit}) => {


        function getLocation() {
            console.log("GEO");
            console.log(navigator.geolocation);
          if (navigator.geolocation) {
            console.log("TESTE1");
            navigator.geolocation.getCurrentPosition(getPosition);
          } else {
              console.log("TESTE2");
                globalAxios.get('/api/newproducts', { params: { coords: null } })
                .then(res => {
                    commit('SET_NEW_PRODUCTS', res.data.data);
                })
                .catch(error => {
                    console.log(error);
                });
          }
        }

        function getPosition(position) {
            console.log("TESTE3");
            console.log(position);
            globalAxios.get('/api/newproducts', { params: { lat: position.coords.latitude, lon: position.coords.longitude } })
            .then(res => {
                commit('SET_NEW_PRODUCTS', res.data.data);
            })
            .catch(error => {
                console.log(error);
            });

        }

        getLocation();



    },
    saveCurrentProduct: ({commit}, product) => {
        commit('SET_PRODUCT', product);
    },
    updateCurrentStores: ({commit}, stores) => {
        commit('SET_CURRENT_STORES', stores);
    },
    setRegistering: ({commit}) => {
        commit('SET_REGISTERING');
    },
    unsetRegistering: ({commit}) => {
        commit('UNSET_REGISTERING');
    }
};

const getters = {
    currentProduct: state => {
        return state.currentProduct;
    },
    currentReview: state => {
        return state.currentReview;
    },
    isReviewed: state => {
        return state.isReviewed;
    },
    reviewMessage: state => {
        return state.reviewMessage;
    },
    reviewSuccess: state => {
        return state.reviewSuccess;
    },
    tags: state => {
        return state.tags;
    },
    registerMessage: state => {
        return state.registerMessage;
    },
    productRegistered: state => {
        return state.productRegistered;
    },
    isRegistering: state => {
        return state.isRegistering;
    },
    reportTypes: state => {
        return state.reportTypes;
    },
    freguesias: state => {
        return state.freguesias;
    },
    concelhos: state => {
        return state.concelhos;
    },
    distritos: state => {
        return state.distritos;
    },
    newProducts: state => {
        return state.newProducts;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
};
