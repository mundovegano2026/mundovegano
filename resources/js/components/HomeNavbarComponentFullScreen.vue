<template>
    <div class="subpage">

        <header id="header" class="main">
            <nav class="navbar">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>           
                        
                    <router-link to="/login" class="pull-right icon-link visible-xs  visible-sm" style="margin-right: 15px">
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
                        <router-link to="#">
                            <li class="nav-item dropdown">
                                <a class="dropdown-toggle hidden-md hidden-lg" data-toggle="dropdown" href="#">
                                    <router-link to="#!" style="display: inline">CATEGORIAS</router-link>
                                    <span class="caret"></span>
                                </a>
                                <a class="dropdown-toggle hidden-sm hidden-xs" data-toggle="dropdown" href="#">
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

        </header>
   
        <!-- Banner -->
        <section >
            <div class="inner inner-title">
                <header>
                    <h1><router-link to="/">Mundo Vegano</router-link></h1>
                </header>
                <appMainSearch class="inner-component" :cat="categories"></appMainSearch>   
            </div>
        </section>
        <header class="subnote bottom inner-component">
            <h2>Procura e partilha<br>
            produtos veganos</h2>
        </header>
        
    </div>
</template>
<script>
    import MainSearch from './inc/MainSearchComponent.vue';
    import SubMenu from './inc/SubMenuComponent.vue';
    import utils from '../utils';
    export default {
        data() {
            return {
                isHome: this.$router.history.current.name == "Home" || this.$router.history.current.name == "Home1"
            }
        },
        watch: {
            $route (to, from){
                this.isHome = this.$router.history.current.name == "Home" || this.$router.history.current.name == "Home1";
            }
        },
        components: {
            appMainSearch: MainSearch,
            appSubMenu: SubMenu
        },
        computed: {
            categories() {
                return this.$store.getters.categories;
            },
            mainCategories() {
                return this.$store.getters.mainCategories;
            },
            hierarchicalCategories() {
                let categoryList = this.categories.filter(c=>c.level==1);
                for(var i = 0; i < categoryList.length; i++) {
                    let category = categoryList[i];
                    category.subCategory = false;
                    let subCategoryList = this.categories.filter(s=>s.parent == category.id);
                    for(var j = 0; j < subCategoryList.length; j++) {
                        let subCategory = subCategoryList[j];
                        subCategory.subCategory = true;
                        categoryList.splice(i+1, 0, subCategory);
                        i++;
                    }
                }
                return categoryList;
            },
            isLogged() {
                return this.$store.getters.isLogged;
            }
        },
        methods: {
            redirectCreateProduct() {
                this.$router.push({path: '/novo'});
            },
            logout () {
                this.$store.dispatch('logout')
            }
        },
        created() {
            utils.removeFlash('body');

            // Submenus
            $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
            });

            // make it as accordion for smaller screens
            if ($(window).width() < 992) {
                $('.dropdown-menu a').click(function(e){
                    e.preventDefault();
                    if($(this).next('.submenu').length){
                        $(this).next('.submenu').toggle();
                    }
                    $('.dropdown').on('hide.bs.dropdown', function () {
                        $(this).find('.submenu').hide();
                    });
                });
            }

        }
    }
</script>