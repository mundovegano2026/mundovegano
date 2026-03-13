require('./bootstrap');


import Vue from 'vue'
import VueRouter from 'vue-router';

import App from './components/AppComponent.vue'
import { routes } from './routes';
import store from './store/store';
import VueSocialSharing from 'vue-social-sharing'

Vue.use(VueSocialSharing);

Vue.use(VueRouter);


Vue.component('navbar', require('./components/NavbarComponent.vue').default);
Vue.component('app', require('./components/AppComponent.vue').default);

var filter = function(text, length, clamp){
  clamp = clamp || '...';
  var node = document.createElement('div');
  node.innerHTML = text;
  var content = node.textContent;
  return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', filter);

const router = new VueRouter({
    mode: 'history',
    routes
  });

// router.afterEach((to, from) => {
  
//     this.$nextTick(function () {
//       console.log("BUILDING");
//     });
// });
  
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App),
  created () {
    const userInfo = localStorage.getItem('user')
    if (userInfo) {
      const userData = JSON.parse(userInfo)
      this.$store.commit('setUserData', userData)
    }
    axios.interceptors.response.use(
      response => response,
      error => {
        if (error.response.status === 401) {
          this.$store.dispatch('logout')
        }
        return Promise.reject(error)
      }
    );
  }
})
  

Vue.config.devtools = false;
Vue.config.debug = false;
Vue.config.silent = true; 