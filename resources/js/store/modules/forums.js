import globalAxios from 'axios'

const state = {
    boards: [],
    forum_posts: [],
    forum_post: {
        title: '',
        text: '',
        comments: [],
        path: ''
    },
    forum_comment: {
        title: '',
        body: ''
    },
    postRegistered: false,
    commentRegistered: false
};

const mutations = {
    'SET_BOARDS' (state, boards) {
        state.boards = boards;
    },
    'SET_POSTS' (state, posts) {
        state.forum_posts = posts;
    },
    'SET_POST' (state, post) {
        state.forum_post = post;
    },
    'REGISTER_POST' (state, post) {
        state.forum_post = post;
        state.postRegistered = true;
    },
    'REGISTER_COMMENT' (state, comment) {
        state.forum_comment = comment;
        state.commentRegistered = true;
    }
};

const actions = {
    fetchBoards: ({commit}) => {
        return globalAxios.get('/api/boards')
        .then(res => {
            commit('SET_BOARDS', res.data.boards);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchPosts: ({commit}, board_id) => {
        return globalAxios.get('/api/posts/' + board_id)
        .then(res => {
            commit('SET_POSTS', res.data.posts);
        })
        .catch(error => {
            console.log(error);
        });
    },
    fetchPost: ({commit}, post_id) => {
        return globalAxios.get('/api/post/' + post_id)
        .then(res => {
            commit('SET_POST', res.data.post);
        })
        .catch(error => {
            console.log(error);
        });
    },
    setPost: ({commit}, post) => {
        commit('REGISTER_POST', post);
    },
    setComment: ({commit}, post) => {
        commit('REGISTER_COMMENT', post);
    }
};

const getters = {
    boards: state => {
        return state.boards;
    },
    forum_posts: state => {
        return state.forum_posts;
    },
    forum_post: state => {
        return state.forum_post;
    },
    forum_comment: state => {
        return state.forum_comment;
    },
    postRegistered: state => {
        return state.postRegistered;
    },
    commentRegistered: state => {
        return state.commentRegistered;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
};