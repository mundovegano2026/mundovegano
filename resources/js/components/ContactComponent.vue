<template>
    <div class="row page-content">
        <div class="main-content col-md-8">

            <h2 v-if="!contactRegistered">Contacto</h2>
            
            <!-- Instructions -->
            <div v-if="!contactRegistered" class="instructions-area">
                <div class="instructions-paragraph">Se quiseres colocar alguma questão ou dar sugestões, opiniões ou informações, deixa mensagem.</div>
            </div>
            <h2 v-else style="text-align: center">Obrigado pelo contacto! <button type="button" class="btn btn-default" @click="resetContact">Submeter novo</button></h2>


            <form v-if="!contactRegistered" class="contact-form" type="multipart/form-data" @submit.prevent="submitContact" style="margin-top: 25px">  

                <!-- Name -->  
                <div v-if="!isLogged" class="form-group">
                    <input type="text" required="required" class="form-control input-lg" name="name" id="name" v-model="contact.name" placeholder="Nome">
                </div>
                <!-- Email -->  
                <div v-if="!isLogged" class="form-group">
                    <input type="email" required="required" class="form-control input-lg" name="email" id="email" v-model="contact.email" placeholder="E-mail">
                </div>
                <div v-if="isLogged">
                    <div v-if="isLogged">Sessão iniciada como {{ user.name }}. <router-link to="/login">Iniciar sessão com utilizador diferente?</router-link></div>
                </div>
                <!-- Topic -->  
                <div class="form-group">
                    <input type="text" required="required" class="form-control input-lg" name="topic" id="topic" v-model="contact.topic" placeholder="Assunto">
                </div>
                <!-- Message -->  
                <div class="form-group">
                    <textarea required="required" v-model="contact.message" placeholder="Mensagem"></textarea>
                </div>

                <button type="submit">ENVIAR</button>

            </form>

        </div>

        <!-- SIDEBAR -->
        <div class="sidebar hidden-xs hidden-sm col-md-4">

            <!-- Search Input
            <div class="input-container">
                <input class="input-field" type="text" placeholder="Username" name="usrnm">
                <i class="fa fa-search icon"></i>
            </div> -->

            <!-- Category List -->
            <sidebar :categories="categories"></sidebar>

        </div>

        <div id="overlay" style="display:none;">
            <div class="spinner"></div>
            <br/>
            A Carregar...
        </div>     
        
    </div>
    
</template>

<script>
    import SideBar from './inc/SideBarComponent.vue';
    import utils from '../utils';

    export default {
        data() {
            return {
                contact: {
                    name: '',
                    email: '',
                    topic: '',
                    message: ''
                }
            }
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                return this.$store.getters.user;
            },
            categories() {
                return this.$store.getters.mainCategories;
            },
            contactRegistered() {
                return this.$store.getters.contactRegistered;
            }
        },
        methods: {
            submitContact() {
                
                $('#overlay').fadeIn();

                let that = this;

                const formData = new FormData();
                formData.append('contact', JSON.stringify(this.contact));
                formData.append('user', JSON.stringify(this.user));

                let requestURL = this.isLogged ? '/api/contact' : '/api/guest-contact';
                // Send register request
                axios({
                    method: 'post',
                    url: requestURL,
                    data: formData,
                    headers: {'content-type': `multipart/form-data; boundary=${formData._boundary}` }
                })
                .then(res => {
                    
                    if(typeof res.data != 'undefined' && typeof res.data != 'undefined') {
                        if(res.data.error) {
                            utils.addVueFlash("danger", ".subpage", res.data.error);
                        } else {
                            this.$store.dispatch('setContact', res.data.contact);
                        }
                    } else {
                        utils.addVueFlash("danger", ".subpage", "Erro ao enviar contacto.");
                    }

                    $('#overlay').fadeOut();
                    
                })
                .catch(error => {
                   
                   utils.addVueFlash("danger", ".subpage", "Erro ao enviar contacto.");

                   $('#overlay').fadeOut();
              
                });
            },
            resetContact() {
                this.$store.dispatch('resetContact');                
                this.contact = {
                    name: '',
                    email: '',
                    topic: '',
                    message: ''
                };
            }
        },
        components: {
            sidebar: SideBar
        }
    }
   
</script>