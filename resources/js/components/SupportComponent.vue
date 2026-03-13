<template>
    <div class="row page-content">
        <div class="main-content col-md-8">

            <h2>Apoiar o Mundo Vegano</h2>
            
            <!-- Instructions -->
            <div class="instructions-area">
                <div class="instructions-paragraph">O Mundo Vegano está a ser construído por todos. Desejamos que esteja a ser construído por ti também.</div>
                <div class="instructions-paragraph">A sobrevivência do Mundo Vegano depende dos donativos e patrocínios que recebe. Faz, por favor, um <a href="#!" onclick="document.getElementById('ppform').submit()">donativo</a>.</div>
                <div class="instructions-paragraph">Se queres patrocinar ou publicitar no Mundo Vegano, <span><router-link to="/contacto">apresenta-nos a tua proposta!</router-link></span></div>
                <div class="instructions-paragraph">Os montantes recebidos são necessários para remunerar os colaboradores que mantêm o site em funcionamento e para fazer crescer o Mundo Vegano. No início de cada mês, o site atualiza <router-link to="/despesas">aqui</router-link> os valores dos montantes recebidos e das despesas que vai tendo.</div>
                <div class="instructions-paragraph">A remuneração dos colaboradores obedece às seguintes regras:</div>
                <div class="instructions-paragraph">
                    <ol>
                        <li>A Remuneração Bruta por Hora de Trabalho (RBHT) é igual para todos os colaboradores.</li>
                        <li>A RBHT é calculada com base no valor da Remuneração Bruta Mensal Média (RBMM) divulgado pelo INE relativo ao ano anterior, considerando-se que um mês tem 22 dias úteis e que um dia de trabalho tem 8 horas, i.e. RBHT=RBMM / (22*8). No ano de 2020, o valor da RBMM foi de 1314€.</li>
                        <li>Se, num determinado mês, o Mundo Vegano não dispuser de liquidez para remunerar os seus colaboradores com o valor da RBHT, a sua remuneração far-se-á distribuindo equitativamente o montante existente em caixa (tendo em conta o número de horas de trabalho prestado por cada colaborador).</li>                        
                    </ol>
                </div>
                <div class="instructions-paragraph">O montante recebido pelo Mundo Vegano que exceder as suas despesas (i.e. as despesas com a remuneração dos colaboradores e com o alojamento do site) será utilizado exclusivamente em favor da causa animal, nomeadamente, fazendo crescer o Mundo Vegano. Esse crescimento traduzir-se-á, de início, na criação de novas funcionalidades para o site e, posteriormente, em dar uma concretização física ao Mundo Vegano. Os moldes em que essa concretização física acontecerá, serão discutidos num espaço de debate que, entretanto, o site disporá.</div>
                <div class="instructions-paragraph">Faz, por favor, um donativo:</div>
            </div>
            
            <form class="contact-form" type="multipart/form-data" @submit.prevent="submitContact" style="margin-top: 25px">  

                <div id="donate-button-container">
                    <div id="donate-button"></div>
      
                </div>

                <!-- <form action="https://www.paypal.com/donate" method="post" target="_top" style="text-align: center" id="ppform">
                    <input type="hidden" name="business" value="D43UAPU5X2D2Y" />
                    <input type="hidden" name="no_recurring" value="0" />
                    <input type="hidden" name="item_name" value="Ajuda a construir um Mundo Vegano" />
                    <input type="hidden" name="currency_code" value="EUR" />
                    <input type="image" src="https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_donate_LG.gif" border="0" name="submit" title="Apoia o Mundo Vegano!" alt="Apoia o Mundo Vegano!" />
                    <img alt="" border="0" src="https://www.paypal.com/pt_PT/i/scr/pixel.gif" width="1" height="1" />
                </form> -->
                <form action="https://www.paypal.com/donate" method="post" target="_top" style="text-align: center" id="ppform">
                    <input type="hidden" name="hosted_button_id" value="UXWMDV5UPLHJW" />
                    <input type="image" src="https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Faça donativos com o botão PayPal" />
                    <img alt="" border="0" src="https://www.paypal.com/pt_PT/i/scr/pixel.gif" width="1" height="1" />
                </form>

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
            }
        },
        methods: {
        },
        mounted() {
            // let externalScript = document.createElement('script')
            // externalScript.setAttribute('src', 'https://www.paypalobjects.com/donate/sdk/donate-sdk.js')
            // document.head.appendChild(externalScript);
            
            // externalScript.addEventListener('load', function() {

            //     PayPal.Donation.Button({
            //     env:'sandbox',
            //     hosted_button_id:'XXR398B6H9TJE',
            //     image: {
            //     src:'https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_donate_LG.gif',
            //     alt:'Donate with PayPal button',
            //     title:'PayPal - The safer, easier way to pay online!',
            //     }
            //     }).render('#donate-button');

            // });


        },
        components: {
            sidebar: SideBar
        }
    }
   
</script>