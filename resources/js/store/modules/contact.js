import globalAxios from 'axios'

const contactModel = {
    name: '',
    email: '',
    topic: '',
    text: ''
}

const state = {
    contactRegistered: false,
    contact: JSON.parse(JSON.stringify(contactModel))
};

const mutations = {
    'REGISTER_CONTACT' (state, contact) {
        state.contact = contact;
        state.contactRegistered = true;
    },
    'RESET_CONTACT' (state) {
        state.contact = JSON.parse(JSON.stringify(contactModel));
        state.contactRegistered = false;
    }
};

const actions = {
    setContact: ({commit}, contact) => {
        commit('REGISTER_CONTACT', contact);
    },
    resetContact: ({commit}, contact) => {
        commit('RESET_CONTACT');
    }
};

const getters = {
    contact: state => {
        return state.contact;
    },
    contactRegistered: state => {
        return state.contactRegistered;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
};