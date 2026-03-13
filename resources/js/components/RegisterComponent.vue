<template>
  <div class="page-content main-content">

    <div v-if="registered">
        <h3>Muito obrigado por te juntares ao Mundo Vegano!</h3>
        <h4 v-if="isRegistering">Continua o teu registo <router-link to="/novo">aqui</router-link></h4>
        <h4 v-else>Começa a visita <router-link to="/">aqui</router-link></h4>
    </div>

    <div v-else>
        <h1>Regista-te no Mundo Vegano!</h1>
        <form @submit.prevent="register">

          <div class="form-group">
              <input type="text" name="name" placeholder="Nome" v-model="name">
          </div>

          <div class="form-group">
              <input type="email" name="email" placeholder="Email" v-model="email">
          </div>
                      
          <div class="form-group">
            <input type="password" name="password" placeholder="Palavra-passe" v-model="password">
          </div>
                      
          <div class="form-group">
            <input type="password" name="confirmpassword" placeholder="Confirmar Palavra-passe" v-model="confirmpassword">
          </div>

          <button type="submit">Registar</button>

          <button type="submit" @click.prevent="$router.go(-1)">Voltar</button>

          <facebook-login class="button"
            appId="120385808817721"
            @login="onLogin"
            @logout="onLogout"
            @get-initial-status="getUserData"
            @sdk-loaded="sdkLoaded"
            @sdk-locale="pt_PT">
            <slot name="login">
              Continuar com o Facebook
            </slot>
          </facebook-login>
        </form>
    </div>
    
    <div id="overlay" style="display:none;">
      <div class="spinner"></div>
      <br/>
      A Carregar...
    </div>     
 
  </div>

</template>

<script>
import facebookLogin from 'facebook-login-vuejs';

export default {
  data () {
    return {
      registered: false,
      email: '',
      confirmpassword: '',
      password: '',
      name: '',
      personalID: '',
      picture: '',
      FB: undefined,
      facebookLogin: false,
      fbEMAIL: ''
    }
  },
  components: {
      facebookLogin
  },
  computed: {
    isLogged() {
        return this.$store.getters.isLogged;
    },
    isRegistering() {
        return this.$store.getters.isRegistering;
    }
  },
  mounted() {
    $('.container.button button').find('img').get(0).nextSibling.remove();
    $('.container.button button').append("Continuar com o Facebook")
  },
  watch: {
    facebookLogin() {
      console.log("CHANGING");
      console.log(this.facebookLogin);
      if(this.facebookLogin)
        this.register();
    }
  },
  methods: {
    register () {

      $('#overlay').fadeIn();

      let email = this.facebookLogin ? this.fbEMAIL : this.email;
      let pwd = !this.facebookLogin ? this.password : 'fblogin';
      let confirmPwd = !this.facebookLogin ? this.confirmpassword : 'fblogin';

      this.$store
        .dispatch('register', {
          email: email,
          password: pwd,
          confirmpassword: confirmPwd,
          name: this.name,
          fbid: this.personalID,
          facebook: this.facebook
        })
        .then(() => {
          // this.$router.push({ name: 'Home' })
          this.registered = true;

          $('#overlay').fadeOut();

        })
        .catch(err => {
          console.log(err);
          $('#overlay').fadeOut();
        })
    },
    logout () {
      this.$store.dispatch('logout')
    },
    getUserData() {
      this.FB.api('/me', 'GET', { fields: 'id,name,email,picture' },
        user => {
          this.personalID = user.id;
          this.email = user.email;
          this.name = user.name;
          this.picture = user.picture.data.url;
          this.facebookLogin = true;
        }
      )
    },
    sdkLoaded(payload) {
      this.isConnected = payload.isConnected
      this.FB = payload.FB
      if (this.isConnected) this.getUserData()
    },
    onLogin() {
      this.isConnected = true
      this.getUserData()
    },
    onLogout() {
      this.isConnected = false;
    }
  }
}
</script>