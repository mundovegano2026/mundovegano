<template>
  <div class="page-content main-content">

    <h1>Recuperar Palavra-Passe</h1>
    <div v-if="!pwdSet">
      
      <div>Cria uma nova palavra-passe</div>
      <form @submit.prevent="confirm">

        <div class="form-group">
          <input type="password" name="password" placeholder="Palavra-passe" v-model="password">
        </div>
                    
        <div class="form-group">
          <input type="password" name="confirmpassword" placeholder="Confirmar Palavra-passe" v-model="confirmpassword">
        </div>                  

        <button type="submit">Confirmar</button>

      </form>

    </div>

    <div v-else>
        <p>Palavra Passe confirmada! Podes efetuar login <router-link to="/login">aqui.</router-link></p>
    </div>
  </div>
</template>

<script>
    import utils from '../utils';
    export default {
      data () {
        return {
          confirmToken: this.$route.query.confirmToken,
          confirmpassword: '',
          password: '',
          pwdSet: false
        }
      },
      computed: {
        isLogged() {
            return this.$store.getters.isLogged;
        }
      },
      methods: {
        confirm () {
          let email = this.facebookLogin ? this.fbEMAIL : this.email;
          
          this.$store
            .dispatch('setnewpwd', {
              confirmToken: this.confirmToken,
              password: this.password,
              confirmpassword: this.confirmpassword
            })
            .then(() => {
               this.pwdSet = true;
            })
            .catch(error => {
              if (error.response) {
                utils.addVueFlash("danger", ".subpage", 'Erro ao definir palavra-passe.');
              }
            })
        }
      }
    }
</script>