<template>
  <div class="page-content main-content">

    <h1>Recuperar Palavra-Passe</h1>
    <div v-if="!emailSent">
      <div>Preenche o email associado à conta</div>
      <form @submit.prevent="recover">

        <div class="form-group">
            <input required="required" type="email" name="email" placeholder="Email" v-model="email">
        </div>              
        <button type="submit">Recuperar</button>

      </form>  
    </div>
    <div v-else>
        <div>Enviámos um email para concluir o processo de renovação da palavra-passe!</div>
    </div>
  </div>
</template>

<script>
    import utils from '../utils';
    export default {
      data () {
        return {
          email: '',
          emailSent: false
        }
      },
      computed: {
        isLogged() {
            return this.$store.getters.isLogged;
        }
      },
      methods: {
        recover () {
          let email = this.facebookLogin ? this.fbEMAIL : this.email;
          
          this.$store
            .dispatch('recover', {
              email: email
            })
            .then(() => {
              this.emailSent = true;
            })
            .catch(error => {
              if (error.response) {
                utils.addVueFlash("danger", ".subpage", 'Erro ao recuperar palavra-passe.');
              }
            })
        }
      }
    }
</script>