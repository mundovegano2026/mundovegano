<template>
  <div class="page-content main-content" style="padding-bottom: 150px">
          
    <h2>Detalhes de utilizador</h2>

    <h1>Muda a Palavra-Passe</h1>
    <form @submit.prevent="updatePassword">
  
        <div class="form-group">
            <input type="password" required name="oldpassword" placeholder="Palavra-passe atual" v-model="oldpassword">
        </div>
                    
        <div class="form-group">
            <input type="password" required name="password" placeholder="Nova Palavra-passe" v-model="password">
        </div>
                    
        <div class="form-group">
            <input type="password" required name="confirmpassword" placeholder="Confirmar Palavra-passe" v-model="confirmpassword">
        </div>
        <div class="col-md-4 col-md-offset-8">
            <button type="submit">Gravar</button>
        </div>
    </form>

    <h1>Elimina a Conta</h1>
    <form @submit.prevent="deleteAccountConfirm">
        <p>Ao eliminar a conta, os dados referentes à mesma não poderão ser recuperados.</p>
  
        <div class="form-group">
            <input type="password" required name="currentpassword" placeholder="Palavra-passe" v-model="currentpassword">
        </div>
        
        <div class="col-md-4 col-md-offset-8">

            <button type="submit">Eliminar</button>
        </div>
    </form>
    
    <form v-if="false" @submit.prevent="updateLocation">

        <h1>Localização</h1>
        <div class="form-group">
            <label for="distrito">Distrito</label>
            <select id="distrito" v-model="distrito" @change="updateConcelho">
                    <option value="">Selecionar</option>
                    <option v-for="distrito in distritoList" :key="distrito.name" :value="distrito.distrito">{{ distrito.distrito }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="concelho">Concelho</label>
            <select id="concelho" v-model="concelho">
                    <option value="">Selecionar</option>
                    <option v-for="concelho in concelhoList" :key="concelho.name" :value="concelho.concelho">{{ concelho.concelho }}</option>
            </select>
        </div> 
        <div class="col-md-4 col-md-offset-8">
            <button type="submit">Gravar</button>
        </div>
    </form>

       <!-- Modal Confirm Delete Account -->
        <div id="deletemodal" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Eliminar Conta</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form">
                            <h3>Tem a certeza que pretende eliminar a sua conta?</h3>
                        </div>

                    </div>
                    <div class="modal-footer row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" @click="deleteAccount" data-dismiss="modal">Confirmar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
  </div>
</template>

<script>
    import utils from '../utils';
    export default {
        data () {
            return {
            currentpassword: '',
            oldpassword: '',
            confirmpassword: '',
            password: '',
            concelhoList: [],
            distritoList: [],
            distrito: '',
            concelho: ''
            }
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                this.distrito = this.$store.getters.user.distrito;
                this.concelho = this.$store.getters.user.concelho;
                return this.$store.getters.user;
            },
            distritos() {
                if(this.user) {
                    this.distrito = this.$store.getters.user.distrito;
                    this.concelho = this.$store.getters.user.concelho;
                }
                return this.$store.getters.distritos;
            }
        },
        watch: {
            '$store.getters.concelhos': function (id) {
                this.concelhoList = this.$store.getters.concelhos
            },
            '$store.getters.distritos': function (id) {
                this.distritoList = this.$store.getters.distritos
            },
            distrito: function() {            
                this.updateConcelho();
            }
        },
        methods: {
            updatePassword () {
            this.$store
                .dispatch('updatePWD', {
                oldpassword: this.oldpassword,
                password: this.password,
                confirmpassword: this.confirmpassword
                })
                .then(() => {
                    utils.addVueFlash("success", ".subpage", "Dados atualizados!");
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 404) {
                        utils.addVueFlash("danger", ".subpage", 'Palavra-passe incorreta.');
                        } else {
                        utils.addVueFlash("danger", ".subpage", 'Erro ao submeter palavra-passe.');
                        }
                    } else {
                        utils.addVueFlash("danger", ".subpage", 'Erro ao submeter palavra-passe.');
                    }
                })
            },
            deleteAccountConfirm ()  {
                $('#deletemodal').modal('show');
            },
            deleteAccount () {
            this.$store
                .dispatch('deleteAccount', {
                currentpassword: this.currentpassword
                })
                .then(() => {
                    utils.addVueFlash("success", ".subpage", "Conta eliminada!");
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 404) {
                        utils.addVueFlash("danger", ".subpage", 'Palavra-passe incorreta.');
                        } else {
                        utils.addVueFlash("danger", ".subpage", 'Erro ao submeter palavra-passe.');
                        }
                    } else {
                        utils.addVueFlash("danger", ".subpage", 'Erro ao submeter palavra-passe.');
                    }
                })
            },
            updateLocation () {
            this.$store
                .dispatch('updateLocation', {
                distrito: this.distrito,
                concelho: this.concelho
                })
                .then(() => {
                    this.$store.dispatch('updateUserLocation', { distrito: this.distrito, concelho: this.concelho });
                    utils.addVueFlash("success", ".subpage", "Dados atualizados!");
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 404) {
                        utils.addVueFlash("danger", ".subpage", 'Localização não encontrada.');
                        } else {
                        utils.addVueFlash("danger", ".subpage", 'Erro ao atualizar localização.');
                        }
                    } else {
                        utils.addVueFlash("danger", ".subpage", 'Erro ao atualizar localização.');
                    }
                })
            },           
            updateConcelho(e) {
                if(typeof e == "undefined") {
                    this.$store.dispatch('fetchConcelhos', this.distrito);
                } else {
                    this.$store.dispatch('fetchConcelhos', $(e.currentTarget).val());
                }
                if(this.distrito=="") this.concelho = "";
            },
            logout () {
            this.$store.dispatch('logout')
            }
        },
        created() {

            var that = this;

            if(!this.distritos.length)
                this.$store.dispatch('fetchDistritos');

        }
    }
</script>