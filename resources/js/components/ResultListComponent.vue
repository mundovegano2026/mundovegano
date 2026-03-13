<template>
    <div>
        <section id="main" class="wrapper">
            <div class="inner">
                <header class="align-center">
                    <h3 v-if="!locationActivated && !distritoLocation"><p class="font-weight-bold">- Para podermos indicar-te os produtos disponíveis perto de ti, permite que acedamos à tua localização, ou indica-nos uma localização <span style="cursor: pointer; font-weight:bold" @click="toggleLocation">AQUI</span> -</p></h3>
                    <div class="filter-area row">
                        <div style="float: right" class="form-group d-block visible-xs visible-sm">
                            <button class="btn" title="Adicionar Filtros" style="width: auto" @click="toggleFilters"><i class="fa fa-filter"></i></button>
                            <button class="btn" title="Definir Lozaliação" style="width: auto" @click="toggleLocation"><i class="fa fa-location-arrow"></i></button>
                        </div>
                        <div class="search-area col-md-8">

                            <div class="form">
                                <div class="form-group row">
                                    <div class="col-xs-12 col-md-6 col-mobile">
                                        <input type="text" name="searchText" id="searchText" v-model="searchText">
                                    </div>
                                    <div class="col-xs-12 col-md-4 col-mobile">
                                        <select v-model="searchCategory" name="main_search_category" id="main_search_category" class="search-field">
                                            <option selected="selected" value="">Todas as categorias</option>
                                            <option  v-for="category in hierarchicalCategories" :key="category.id" :class="{ optionGroup: !category.subCategory, optionChild: category.subCategory }">{{ (category.subCategory ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '') + category.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-12 col-md-2 col-mobile search-button-mobile" style="text-align: left; padding-left: 10px">
                                        <button class="btn" title="Pesquisar" style="width: auto1" @click="redoSearch"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="sort-area col-xs-12 col-md-4" style="text-align: right">

                            <div class="form-inline row">
                                <div class="form-group mobile-group">
                                    Ordenar por:
                                </div>
                                <div class="form-group">
                                    <select id="sortby" v-model="sortBy" @change="changeSorting">
                                            <option v-if="distritoLocation || locationActivated" value="PROX">Proximidade</option>
                                            <option value="REL">Relevância</option>
                                            <option value="RATE">Classificação</option>
                                            <option value="DATE">Mais Recente</option>
                                            <option value="NAME">Nome</option>
                                            <option value="PRICE">Preço</option>
                                    </select>
                                </div>
                                <div class="form-group hidden-xs hidden-sm">
                                    <button class="btn" title="Adicionar Filtros" style="width: auto" @click="toggleFilters"><i class="fa fa-filter"></i></button>
                                    <button class="btn" title="Definir Lozaliação" style="width: auto" @click="toggleLocation"><i class="fa fa-location-arrow"></i></button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div v-if="dataLoaded" class="result-display mobile-results">
                        <div class="row" v-for="group in visibleProductChunks" :key="group[0].id">
                            <product v-for="product in group" :key="product.id" :prod="product"></product>
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

                        <div v-if="!visibleProductChunks.length">
                            <h3>Sem Resultados<span v-if="locationActivated || distritoLocation"> nas proximidades</span></h3>
                            <h3 v-if="!distritoLocation"><p class="font-weight-bold">Gere as definições de localização <span style="cursor: pointer; font-weight:bold" @click="toggleLocation">AQUI</span></p></h3>
                        </div>
                    </div>
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

                            <!-- <fieldset>
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
                            </fieldset>            -->
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
                            <button type="button" class="btn btn-default" @click="redoSearch" data-dismiss="modal">Aplicar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

       <!-- Modal Location -->
        <div id="locationmodal" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Localização</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form">

                            <button class="btn clear-btn" title="Limpar Formulário" @click="clearLocation"><i class="fa fa-eraser"></i></button>

                            <fieldset>
                                <div class="form-group">
                                    <label for="searchDistance">Distância Máx.</label>
                                    <select id="searchDistance" v-model="searchDistance">
                                            <option value="">Selecionar</option>
                                            <option :selected="searchDistance == 5" value="5">5</option>
                                            <option :selected="searchDistance == 10" value="10">10</option>
                                            <option :selected="searchDistance == 20" value="20">20</option>
                                            <option :selected="searchDistance == 50" value="50">50</option>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label for="distritoLocation">Distrito</label>
                                    <select id="distritoLocation" v-model="distritoLocation" @change="updateConcelho">
                                            <option value="">Selecionar</option>
                                            <option v-for="distrito in distritoList" :key="distrito.name" :value="distrito.distrito">{{ distrito.distrito }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="concelhoLocation">Concelho</label>
                                    <select id="concelhoLocation" v-model="concelhoLocation">
                                            <option value="">Selecionar</option>
                                            <option v-for="concelho in concelhoList" :key="concelho.name" :value="concelho.concelho">{{ concelho.concelho }}</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                    <div class="modal-footer row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" @click="redoSearch" data-dismiss="modal">Aplicar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>

</template>
<script>
    import Product from './inc/ProductComponent.vue';
    export default {
        data() {
            return {
                locationActivated: false,
                searchDistance: 5,
                location:null,
                gettingLocation: false,
                errorStr:null,
                dataLoaded: false,
                productChunksCurrentPage: 1,
                productChunksPerPage: 1, // How many groups of 4 products
                sortBy: "REL",
                concelhoList: [],
                distritoList: [],
                distritoLocation: '',
                concelhoLocation: '',
                distrito: '',
                concelho: '',
                brand: '',
                store: '',
                searchText: this.$route.params.searchText,
                searchCategoryInput: this.$route.params.searchCategory,
                searchCategory: ""
            }
        },
        components: {
            product: Product
        },
        watch: {
            '$store.getters.concelhos': function (id) {
                this.concelhoList = this.$store.getters.concelhos
            },
            '$store.getters.distritos': function (id) {
                this.distritoList = this.$store.getters.distritos
            },
            searchCategory() {
                return typeof this.searchCategoryInput !== "undefined" ? this.searchCategoryInput : "";
            }
        },
        computed: {
            cat() {
                return this.$store.getters.categories;
            },
            productList() {
                return this.$store.getters.currentProductList.filter(val => val.relevance_score != 0);
            },
            groupedProducts() {
                return _.chunk(this.productList, 4);
            },
            visibleProductChunks() {
                let endChunk = this.productChunksCurrentPage * this.productChunksPerPage;
                let firstChunk = endChunk - this.productChunksPerPage;
                return this.groupedProducts.slice(firstChunk, endChunk);
            },
            productChunksPageCount() {
                return Math.ceil(this.groupedProducts.length / this.productChunksPerPage);
            },
            distritos() {
                console.log(this.$store.getters.distritos);
                return this.$store.getters.distritos;
            },
            user() {
                return this.$store.getters.user;
            },
            hierarchicalCategories() {
                let categoryList = this.cat.filter(c=>c.level==1);
                for(var i = 0; i < categoryList.length; i++) {
                    let category = categoryList[i];
                    category.subCategory = false;
                    let subCategoryList = this.cat.filter(s=>s.parent == category.id);
                    for(var j = 0; j < subCategoryList.length; j++) {
                        let subCategory = subCategoryList[j];
                        subCategory.subCategory = true;
                        categoryList.splice(i+1, 0, subCategory);
                        i++;
                    }
                }
                return categoryList;
            }
        },
        methods: {
            clearForm() {
                this.brand = '';
                this.store = '';
                this.distrito = '';
                this.concelho = '';
            },
            clearLocation() {
                this.distritoLocation = '';
                this.concelhoLocation = '';
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
            toggleFilters() {
                $('#filters').modal('show');
            },
            toggleLocation() {
                $('#locationmodal').modal('show');
            },
            confirmFilter() {
                $('#overlay').fadeIn();
                var that = this;
                // Fix for null location
                position = position == null ? { lat: null, lon: null } : position;

                // this.fetchProducts(function() {
                //     that.dataLoaded = true;
                //     $('#overlay').fadeOut();
                // });
                this.$store.dispatch('fetchProductList', { distance: this.searchDistance, lat: position.lat, lon: position.lon, user: this.user, searchCategory: this.searchCategory, searchText: this.searchText, order: this.sortBy, filter: { distrito: this.distritoLocation || 'null', concelho: this.concelhoLocation || 'null', brand: this.brand || 'null', store: this.store || 'null' } })
                .then(res=> {
                    callback();
                });

            },
            confirmLocation() {
                $('#overlay').fadeIn();
                var that = this;

                this.fetchProducts(function() {
                    that.dataLoaded = true;
                    $('#overlay').fadeOut();
                }, null);


                // this.fetchProducts(function() {
                //     that.dataLoaded = true;
                //     $('#overlay').fadeOut();
                // });
            },
            changeSorting(el) {
                $('#overlay').fadeIn();
                var that = this;
                this.fetchProducts(function() {
                    that.dataLoaded = true;
                    $('#overlay').fadeOut();
                });
            },
            fetchProducts(callback, position) {

                // Fix for null location
                position = position == null ? { lat: null, lon: null } : position;

                this.$store.dispatch('fetchProductList', { distance: this.searchDistance, lat: position.lat, lon: position.lon, user: this.user, searchCategory: this.searchCategory, searchText: this.searchText, order: this.sortBy, filter: { distrito: this.distritoLocation || 'null', concelho: this.concelhoLocation || 'null', brand: this.brand || 'null', store: this.store || 'null' } })
                .then(res=> {
                    callback();
                });
            },
            redoSearch() {

                var that = this;
                $('#overlay').fadeIn();
                that.dataLoaded = false;


                if(("geolocation" in navigator)) {
                    this.gettingLocation = true;
                    // get position
                    navigator.geolocation.getCurrentPosition(pos => {
                        this.gettingLocation = false;
                        this.location = pos;

                        this.fetchProducts(function() {
                            that.dataLoaded = true;
                            $('#overlay').fadeOut();
                        }, { lat: that.location.coords.latitude, lon: that.location.coords.longitude });
                        // this.getProd(pos.coords);
                    }, err => {
                        console.log("Geo Location Error 1");
                        this.fetchProducts(function() {
                            that.dataLoaded = true;
                            $('#overlay').fadeOut();
                        }, null);
                        this.gettingLocation = false;
                        this.errorStr = err.message;
                    });

                    if(!this.distritos.length)
                        this.$store.dispatch('fetchDistritos');
                } else {

                    this.fetchProducts(function() {
                            that.dataLoaded = true;
                            $('#overlay').fadeOut();
                        }, null);
                }


                // this.fetchProducts(function() {
                //     that.dataLoaded = true;
                //     $('#overlay').fadeOut();
                // });

            }
        },
        created() {

            var that = this;

            if(("geolocation" in navigator)) {

                // Get User Location
                this.gettingLocation = true;
                navigator.geolocation.getCurrentPosition(pos => {
                    this.locationActivated = true;
                    this.sortBy = 'PROX';
                    this.gettingLocation = false;
                    this.location = pos;
                    $('#overlay').fadeIn();
                    this.fetchProducts(function() {
                        that.dataLoaded = true;
                        $('#overlay').fadeOut();
                    }, { lat: that.location.coords.latitude, lon: that.location.coords.longitude });

                }, err => {
                    console.log("Geo Location Error Created ");
                    $('#overlay').fadeIn();
                    this.fetchProducts(function() {
                        that.dataLoaded = true;
                    }, null);
                    this.gettingLocation = false;
                    this.errorStr = err.message;
                });

                if(!this.distritos.length)
                    this.$store.dispatch('fetchDistritos');

            } else {

                $('#overlay').fadeIn();
                this.fetchProducts(function() {
                        that.dataLoaded = true;
                    }, null);

                if(!this.distritos.length)
                    this.$store.dispatch('fetchDistritos');

            }

        }
    }

</script>
