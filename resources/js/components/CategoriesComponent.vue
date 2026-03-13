<template>
    <div v-if="dataLoaded" class="categories-component">
        <section id="main" class="wrapper">
            <div class="inner">


            <div class="filter-area row">
                <div class="search-area col-md-8">

                </div>
                <div class="sort-area col-xs-12 col-md-4" style="text-align: right">         
                    
                    <div class="form-inline row">
                        <div class="form-group mobile-group">
                            Ordenar por: 
                        </div>
                        <div class="form-group">
                            <select id="sortby" v-model="sortBy" @change="changeSorting">
                                    <option value="REL">Relevância</option>
                                    <option value="RATE">Classificação</option>
                                    <option value="DATE">Mais Recente</option>
                                    <option value="NAME">Nome</option>
                                    <option value="PRICE">Preço</option>
                                    <option v-if="user && (user.distrito || user.concelho)" value="PROX">Proximidade</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn" title="Adicionar Filtros" style="width: auto" @click="toggleFilters"><i class="fa fa-filter"></i></button>
                        </div>
                    </div>

                </div>
            </div>
            <header class="align-center">
                <h2>{{ currentCategory.name }}</h2>
                <div class="row">
                    <!-- <div class="col-md-3" v-for="subCat in category.subCategories" :key="subCat.id" >
                        <subCategory :cat="subCat"></subCategory>
                    </div> -->

                    <vue-glide class="mini" v-if="currentCategory.subCategories.length" :perView=carouselLimit :bound=true @glide:mount-after="doit">
                        <vue-glide-slide
                        v-for="subCat in currentCategory.subCategories"
                        :key="subCat.id">                                
                           <!-- <subCategory :cat="subCat"></subCategory> -->
                           <router-link :to="`/categorias/${subCat.id}`" style="text-decoration: none">
                                <div class="frame">
                                    <span class="framed-text">
                                            {{ subCat.name }}
                                    </span>
                                </div>
                           </router-link>
                        </vue-glide-slide>
                        <template slot="control">
                            <button :class="{hidden: currentCategory.subCategories.length<carouselLimit}" class="control-button left-control" data-glide-dir="<">&lt;</button> 
                            <button :class="{hidden: currentCategory.subCategories.length<carouselLimit}" class="right-control" data-glide-dir=">">&gt;</button>
                        </template>
                    </vue-glide>

                </div>



                <div v-if="visibleProductChunks.length" style="margin-top: 50px">
                    <div class="row" v-for="group in visibleProductChunks" :key="group[0].id">
                        <product v-for="product in group" :key="product.id" :prod="product"></product>
                    </div>
                </div>
                <div v-else>
                    <h4>Ainda não existem produtos nesta categoria. Adiciona o primeiro <strong><router-link to="/novo">aqui</router-link></strong>!</h4>
                </div>
                                    
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li v-if="productChunksCurrentPage > 1" class="page-item"><a class="page-link" href="#!" @click="productChunksCurrentPage--">Anterior</a></li>
                        <template v-if="productChunksPageCount > 1">
                            <li class="page-item" v-for="page in productChunksPageCount" :class="[{ active: page == productChunksCurrentPage}]" :key="page"><a class="page-link" href="#!" @click="productChunksCurrentPage = page">{{ page }}</a></li>
                        </template>
                        <li v-if="productChunksPageCount > 1 && productChunksPageCount > productChunksCurrentPage" class="page-item"><a class="page-link" href="#!" @click="productChunksCurrentPage++">Próxima</a></li>
                    </ul>
                </nav>
            </header>

            </div>
        </section>
                <div id="overlay" style="display:none;">
            <div class="spinner"></div>
            <br/>
            A Carregar...
        </div>

        <!-- Modal Filters -->
        <div id="filters" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Filtrar Pesquisa</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form">
                            
                            <button class="btn clear-btn" title="Limpar Formulário" @click="clearForm"><i class="fa fa-eraser"></i></button>
                           
                            <fieldset>
                                <legend>Localização</legend>
                                <div class="form-group">
                                    <label for="distrito">Distrito</label>
                                    <select id="distrito" v-model="distrito" @change="updateConcelho">
                                            <option value="">Selecionar</option>
                                            <option v-for="distrito in distritoList" :key="distrito.name" :value="distrito.distrito">{{ distrito.distrito }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="distrito">Concelho</label>
                                    <select id="distrito" v-model="concelho">
                                            <option value="">Selecionar</option>
                                            <option v-for="concelho in concelhoList" :key="concelho.name" :value="concelho.concelho">{{ concelho.concelho }}</option>
                                    </select>
                                </div> 
                            </fieldset>           
                            <!-- Brand -->
                            <div class="form-group">
                                <label for="brand">Marca</label>
                                <input type="text" class="form-control input-lg" name="brand" id="brand" @keyup="fetchBrand" v-model="brand" autocomplete="off">
                                <div id="brandList" class="suggestion-list"></div>
                            </div>         
                            <!-- Store -->
                            <div class="form-group">
                                <label for="store">Loja</label>
                                <input type="text" class="form-control input-lg" name="store" id="store" @keyup="fetchStore" v-model="store" autocomplete="off">
                                <div id="storeList" class="suggestion-list"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" @click="confirmFilter" data-dismiss="modal">Aplicar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    
</template>
<script>
    import SubCategory from './inc/SubCategoryComponent.vue';
    import Product from './inc/ProductComponent.vue';
    import { Glide, GlideSlide } from 'vue-glide-js';
    import 'vue-glide-js/dist/vue-glide.css';
    export default {
        data() {
            return {
                dataLoaded: false,
                productChunksCurrentPage: 1,
                productChunksPerPage: 3, // How many groups of 3 products
                carouselLimit: window.matchMedia("(max-width: 575.98px)").matches ? 2 : 5,
                sortBy: "REL",
                concelhoList: [],
                distritoList: [],
                distrito: '',
                concelho: '',
                brand: '',
                store: '',
            }
        },
        watch: {
            '$store.getters.concelhos': function (id) {
                this.concelhoList = this.$store.getters.concelhos
            },
            '$store.getters.distritos': function (id) {
                this.distritoList = this.$store.getters.distritos
            }
        },
        components: {
            subCategory: SubCategory,
            product: Product,
            [Glide.name]: Glide,
            [GlideSlide.name]: GlideSlide
        },
        computed: {
            currentCategory() {
                return this.$store.getters.currentCategory;
            },
            groupedProducts() {
                return _.chunk(this.currentCategory.productList, 4);
            },
            visibleProductChunks() {
                let endChunk = this.productChunksCurrentPage * this.productChunksPerPage;
                let firstChunk = endChunk - this.productChunksPerPage;
                return this.groupedProducts.slice(firstChunk, endChunk);
            },
            productChunksPageCount() {
                return Math.ceil(this.groupedProducts.length / this.productChunksPerPage);
            },
            user() {
                return this.$store.getters.user;
            },
            distritos() {
                console.log(this.$store.getters.distritos);
                return this.$store.getters.distritos;
            }
        },
        methods: { 
            doit() {
                let maxHeight=0;
                $(".frame").each(function(){
                if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
                });

                $(".frame").height(maxHeight);
            },
            clearForm() {
                this.brand = '';
                this.store = '';
                this.distrito = '';
                this.concelho = '';
            },
            fetchBrand() {
                /* Autocomplete Brand */
                let that = this;
                if(that.brand.length > 2)
                {
                    axios.get('/api/brands/fetch/' + that.brand)
                    .then(res => {
                        let suggestionList = '<ul class="dropdown-menu" style="display:block; position:relative">';

                        $(res.data.data).each(function(i, val){
                            suggestionList += '<li class="suggestion"><a href="#!">' + val.name + '</a></li>';                   
                        
                        }); 
                        suggestionList += '</ul>';
                        $('#brandList').fadeIn();  
                        $('#brandList').html(suggestionList);

                        $('li.suggestion a').click(function(e) {
                            let brand = $(e.currentTarget).text();
                            $('#brand').val(brand);  
                            that.brand = brand;  
                            $('#brandList').fadeOut();  
                        });
                    })
                    .catch(error => {
                        console.log(error);
                    });
                    
                } 

            },   
            fetchStore() {
                /* Autocomplete Brand */
                let that = this;
                if(that.store.length > 2)
                {
                    axios.get('/api/stores/fetchname/' + that.store)
                    .then(res => {
                        let suggestionList = '<ul class="dropdown-menu" style="display:block; position:relative">';

                        $(res.data.data).each(function(i, val){
                            suggestionList += '<li class="suggestion"><a href="#!">' + val.name + '</a></li>';                   
                        
                        }); 
                        suggestionList += '</ul>';
                        $('#storeList').fadeIn();  
                        $('#storeList').html(suggestionList);

                        $('li.suggestion a').click(function(e) {
                            let store = $(e.currentTarget).text();
                            $('#store').val(store);  
                            that.store = store;  
                            $('#storeList').fadeOut();  
                        });
                    })
                    .catch(error => {
                        console.log(error);
                    });
                    
                } 

            },           
            updateConcelho(e) {
                this.$store.dispatch('fetchConcelhos', $(e.currentTarget).val());
            },
            changeSorting(el) {
                $('#overlay').fadeIn();
                var that = this;
                this.fetchProducts(function() {
                    that.dataLoaded = true;
                    $('#overlay').fadeOut();
                });
            },
            fetchProducts(callback) {

                if(this.$route.params.id){
                    this.$store.dispatch('fetchCategoryData', { id: this.$route.params.id, order: this.sortBy, filter: { distrito: this.distrito || 'null', concelho: this.concelho || 'null', brand: this.brand || 'null', store: this.store || 'null' } })
                    .then(res=> {
                        callback();
                    });
                }
                else {
                    this.$store.dispatch('fetchCategoryData', { name: this.$route.params.name, order: this.sortBy, filter: { distrito: this.distrito || 'null', concelho: this.concelho || 'null', brand: this.brand || 'null', store: this.store || 'null' } })
                    .then(res=> {
                        callback();
                    });
                }

            },
            toggleFilters() {
                $('#filters').modal('show');
            },
            confirmFilter() {
                $('#overlay').fadeIn();
                var that = this;
                this.fetchProducts(function() {
                    that.dataLoaded = true;
                    $('#overlay').fadeOut();
                });
            }
        },
        created() {

            let that = this; 

            this.fetchProducts(
                function() {
                    that.dataLoaded = true;
                }
            )

            if(!this.distritos.length) {
                this.$store.dispatch('fetchDistritos');
            }

            // Remove modal transparency when accessed from modal
            $('.modal-backdrop').remove();
            $('.modal-open').removeClass('modal-open');

        }
    }
   
</script>