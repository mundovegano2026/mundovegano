<template>
    <div class="row page-content">
        <div class="main-content col-md-8">

            <h2>Sobre</h2>

            <div>
                <p>Muitos bens de consumos (alimentos, roupa, calçado, produtos de higiene, produtos de limpeza, etc.) são de origem animal, contêm produtos de origem animal ou são testados em animais. Para que esses bens sejam produzidos, os animais são sujeitos a sofrimentos extremos e, grande parte das vezes, a vidas de torturas continuadas.</p>

                <p>Felizmente, existem cada vez mais alternativas de igual ou melhor qualidade que nos proporcionam igual ou maior satisfação, prazer e conforto. Ao consumirmos essas alternativas, deixamos de ser cúmplices de atos de crueldade.
                Para além disso, o consumo dessas alternativas apresenta outras vantagens: contribui para que sejamos mais saudáveis e não destrói a Natureza. Com efeito, a produção animal é uma das atividades mais poluentes e grande parte dos alimentos de origem animal prejudica a nossa saúde.</p>

                <p>É, pois, muito importante que passemos a consumir produtos veganos.</p>

                <p>Há, no entanto, tipos de produtos para os quais não é fácil encontrar alternativas veganas. Por isso aqui está o Mundo Vegano:</p>

                <p>   
                    <ul>
                        <li><strong>Sempre que precisares de um produto, pesquisa no Mundo Vegano as alternativas veganas que tens à tua disposição.</strong></li>

                        <li><strong>Sempre que encontrares um produto vegano, confere, por favor, se ele já consta no Mundo Vegano; caso não conste, informa-nos da sua existência através de <router-link to="/novo">partilha um novo produto</router-link>; caso conste, mas disponhas de mais informações relevantes sobre o produto (como seja, outras lojas onde é possível encontrá-lo), comunica-nos essas informações através de <router-link to="/contacto">contacto</router-link> ou de um comentário.</strong></li>
                    </ul>
                </p>
                
                
                <p>A atenção do Mundo Vegano centra-se naqueles tipos de produtos em que é ainda escassa a oferta alternativas veganas; daí as categorias e sub-categorias disponíveis.</p>

                <p>Muito obrigado por construíres connosco um Mundo Vegano.</p>
            </div>
                    
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
         
        
    </div>
</template>

<script>
    import SideBar from './inc/SideBarComponent.vue';

    export default {
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
        components: {
            sidebar: SideBar
        }
    }
   
</script>