<template>
    <div>

        <header id="header">
            <!-- <div class="inner"> -->
                <nav class="navbar">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>          
                        <router-link v-if="!isLogged" to="/login" class="pull-right icon-link visible-xs  visible-sm" style="margin-right: 15px">
                            Login
                        </router-link>
                        <router-link to="/" class="pull-right icon-link visible-xs  visible-sm">
                            <i class="fa fa-search" style="padding-right: 20px; padding-top: 20px; font-size: 20px"></i>
                        </router-link>
                        <router-link to="/novo" class="pull-right icon-link visible-xs  visible-sm">
                            <i class="fa fa-plus-square" style="padding-right: 20px; padding-top: 20px; font-size: 20px"></i>
                        </router-link> 
                    </div>
                    <!-- Collection of nav links, forms, and other content for toggling -->
                    <div id="navbarCollapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <router-link to="/">INÍCIO</router-link>
                            <router-link to="#!" class="dropdown-toggle stopProp">
                                <li class="nav-item dropdown">
                                    <a class="hidden-md hidden-lg" data-toggle="dropdown" style="width: 100%" >
                                        CATEGORIAS
                                        <span class="caret"></span>
                                    </a>
                                    <a class="hidden-sm hidden-xs" data-toggle="dropdown" href="#">
                                        <router-link to="/categorias">CATEGORIAS</router-link>
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li v-for="category in mainCategories" :key="category.id">
                                            <router-link :to="`/categorias/${category.id}`">{{ category.name }}</router-link>
                                            <app-sub-menu v-if="category.subCategories.length" :list="category.subCategories" link="/categorias/"></app-sub-menu>
                                        </li>
                                    </ul>
                                </li>
                            </router-link>
                            <router-link to="/novo">PARTILHA NOVO PRODUTO</router-link>
                            <router-link to="/sobre">SOBRE</router-link>
                            <router-link to="/contacto">CONTACTO</router-link>
                            <router-link to="/apoiar">APOIAR O MV</router-link>
                            <!-- <router-link to="/forum">FÓRUM</router-link> -->
                            <router-link v-if="!isLogged" to="/login" class="pull-right">Iniciar Sessão</router-link>
                            <hr class="hidden-md hidden-lg">
                            <a v-if="isLogged" class="pull-right" href="#!" @click="logout()">
                                <i class="fa fa-power-off nav-icon-link"></i>
                                <span class="nav-text-link">TERMINAR SESSÃO</span>
                                </a>
                            <router-link v-if="isLogged" to="/conta" class="pull-right">
                                <i class="fa fa-cog nav-icon-link"></i>
                                <span class="nav-text-link">DEFINIÇÕES</span>
                            </router-link>
                        </ul>
                    </div>
                </nav>
            <!-- </div> -->
        </header>
        <section id="banner">
            <div class="inner">
                <header>
                    <h1><router-link to="/">Mundo Vegano</router-link></h1>
                </header>
            </div>
        </section>
        
    </div>
</template>
<script>
    import utils from '../utils';
    import SubMenu from './inc/SubMenuComponent.vue';
    export default {
        computed: {
            mainCategories() {
                return this.$store.getters.mainCategories;
            },
            isLogged() {
                return this.$store.getters.isLogged;
            }
        },
        methods: {
            logout () {
                this.$store.dispatch('logout')
            } 
        },
        components: {
            appSubMenu: SubMenu
        },
        created() {
            utils.removeFlash('body');
        }
    }
</script>