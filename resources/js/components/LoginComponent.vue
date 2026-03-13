<template>
  <div class="page-content main-content">

    <h1>Iniciar Sessão</h1>
    <p><router-link to="/forgotpwd">Esqueci a palavra-passe.</router-link></p>
    <div>Não tens conta? <router-link to="/registar">Regista-te aqui.</router-link></div>
    <form @submit.prevent="login">

      <div class="form-group">
          <input required="required" type="email" name="email" placeholder="Email" v-model="email">
      </div>
                  
      <div class="form-group"> 
        <input required="required" type="password" name="password" placeholder="Palavra-passe" v-model="password">
      </div>

      <button type="submit">Entrar</button>
      <!-- <fbLogin></fbLogin> -->
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
        <!-- DEBUG FACEBOOK
        <div v-if="isConnected" class="information">
          <h1>My Facebook Information</h1>
          <div class="well">
            <div class="list-item">
              <img :src="picture">
            </div>
            <div class="list-item">
              <i>{{name}}</i>
            </div>
            <div class="list-item">
              <i>{{email}}</i>
            </div>
            <div class="list-item">
              <i>{{personalID}}</i>
            </div>
          </div>
        </div> -->
    </form>
  </div>
</template>

<script>
    import utils from '../utils';
    // import fbLogin from './inc/FacebookLogin.vue';
    import facebookLogin from 'facebook-login-vuejs'
    export default {
      data () {
        return {
          email: '',
          password: '',
          isConnected: false,
          name: '',
          personalID: '',
          picture: '',
          FB: undefined,
          facebookLogin: false,
          fbEMAIL: ''
        }
      },
      components: {
          // fbLogin: fbLogin
          facebookLogin
      },
      mounted() {
        $('.container.button button').find('img').get(0).nextSibling.remove();
        $('.container.button button').append("Continuar com o Facebook")
      },
      // created() {
      //   (function(d){
      //     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
      //     js = d.createElement('script'); js.id = id; js.async = true;
      //     js.src = "https://connect.facebook.net/es_LA/all.js";
      //     d.getElementsByTagName('head')[0].appendChild(js);
      //   }(document));
      // },
      watch: {
        facebookLogin() {
          console.log("CHANGING");
          console.log(this.facebookLogin);
          if(this.facebookLogin)
            this.login();
        }
      },
      computed: {
        isLogged() {
            return this.$store.getters.isLogged;
        },
        isRegistering() {
            return this.$store.getters.isRegistering;
        }
      },
      methods: {
        login () {

          let that= this;
          let email = this.facebookLogin ? this.fbEMAIL : this.email;
          
          this.$store
            .dispatch('login', {
              email: email,
              password: this.password,
              facebook: this.facebookLogin,
              fbid: this.personalID
            })
            .then(() => {
              if(that.isRegistering) {
                  this.$router.push({ name: 'NewProduct'});
              } else {
                  if(!isNaN(this.$route.query.prod)) {
                    this.$router.push({ path: '/artigos/' + this.$route.query.prod  });
                  } else {
                    this.$router.push({ name: 'Home' });
                  }
              }
            })
            .catch(error => {
              if (error.response) {
                if (error.response.status == 404) {
                  utils.addVueFlash("danger", ".subpage", 'Nome do utilizador ou palavra passe errados.');
                } else if(error.response.status == 451) {
                  this.registerFB();
                } else {
                  utils.addVueFlash("danger", ".subpage", 'Erro ao validar credenciais.');
                }
              } else {
                utils.addVueFlash("danger", ".subpage", 'Erro ao validar credenciais.');
              }
            })
        },
        registerFB () {
          this.$store
            .dispatch('register', {
              email: this.fbEMAIL,
              password: 'fblogin',
              confirmpassword: 'fblogin',
              name: this.name,
              facebook: this.facebookLogin,
              fbid: this.personalID
            })
            .then(() => {
              this.$router.push({ name: 'Home' })
            })
            .catch(err => {
              console.log(err)
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