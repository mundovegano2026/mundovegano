import Vue from 'vue';
import Vuex from 'vuex';

import categories from './modules/categories';
import tags from './modules/tags';
import brands from './modules/brands';
import products from './modules/products';
import auth from './modules/auth';
import forums from './modules/forums';
import contact from './modules/contact';
import search from './modules/search';
import user from './modules/user';

import * as actions from './actions';

Vue.use(Vuex);

export default new Vuex.Store({
    actions,
    modules: {
        categories,
        tags,
        products,
        auth,
        forums,
        contact,
        brands,
        search,
        user
    }
});