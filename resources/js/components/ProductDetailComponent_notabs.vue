<template>
    <div class="row page-content">

        <div v-if="product.name!=''" class="main-content col-md-8">

            <!-- Category Link Badges -->
            <div v-if="product.category" class="path-container">
                <span class="badge badge-primary" style="margin-right: 5px" v-for="cat in product.category.path.split('/')" :key="cat">
                    <router-link :to="`/categorias-nome/${cat}`">{{ cat }}</router-link>
                </span>
                <span class="badge badge-primary" style="margin-right: 5px">
                    <router-link :to="`/categorias-nome/${product.category.name}`">{{ product.category.name }}</router-link>
                </span>
            </div>            

            <!-- Product Title -->
            <h2>{{ product.name }}</h2>
            <div class="product-date">
                {{ product.created_at }} - {{ product.user ? product.user.name : (product.admin ? product.admin.name : "") }} - {{ product.commentCount + (product.commentCount == 1 ? ' comentário' : ' comentários') }}
            </div>
            <div v-if="product.score > 0" class="product-score">
                <rating :score="product.score" idKey="general_score"></rating>
            </div>

            <!-- Product Images Display -->
            <div class="image-wrapper">
                <div class="main-image-container">
                    <img :src="'/' + product.image" id="main-image" style="max-width: 100%">
                </div>
            </div>
            <div class="image-carousel row">
                <div class="col-md-3" v-for="image in product.images" :key="image.id">
                    <img class="image-carousel-img" @click="changeImage" :src="'/storage/product_images/' + image.path">
                </div>
            </div>
            
            <!-- Product Details -->
            <!-- <div class="product-detail-area" v-if="product.obs">
                <template v-for="(line, index) in product.obs.split('\n')" >{{line}}<br :key="index"></template>
            </div> -->
            <div class="product-detail-area" v-html="product.obs">
            </div>

            <hr>

            <!-- Brand -->
            <p v-if="product.brand"><strong>MARCA:</strong> {{ product.brand.name }}</p>

            <!-- Stores -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-inner">
                        Onde Comprar 
                        <!-- <a href="#!" class="small-link" @click="addingStore=!addingStore">Encontrei noutra loja</a> -->
                        <a href="#!" class="small-link" data-toggle="modal" data-target="#storeModal">Encontrei noutra loja</a>
                    
                    </div>
                </div>
                <div class="panel-body">
                    <div v-if="!moreStoresVisible">
                        <div class="list-group">
                            <span v-for="store in filteredStoreChunks" :storeId="store.id" :key="store.id">
                                <a href="#!" @click="openStore(store.id)" class="list-group-item list-group-item-action flex-column align-items-start" >
                                    <div class="d-flex w-100 justify-content-between">
                                    <h4 class="store-detail">{{ store.name }}</h4>
                                    <small class="text-muted">{{ store.address }}</small>
                                    </div>
                                </a>
                            </span>
                        </div>
                        <p v-if="filteredStores.length>3" class="link-click" @click="moreStoresVisible = !moreStoresVisible">Ver mais</p>
                    </div>
                    <div v-else>
                        <div class="list-group">
                            <a href="#!" @click="openStore(store.id)" class="list-group-item list-group-item-action flex-column align-items-start"  v-for="store in filteredStores" :key="store.id">
                                <div class="d-flex w-100 justify-content-between">
                                <h4 class="store-detail">{{ store.name }}</h4>
                                <small class="text-muted">{{ store.address }}</small>
                                </div>

                            </a>
                        </div>
                        <p class="link-click" @click="moreStoresVisible = !moreStoresVisible">Ver menos</p>
                    </div>
                    <!-- <div v-if="!moreStoresVisible">
                        <p class="existing-store" v-for="store in filteredStores" :key="store.id">{{ store.name + (store.address ? (' - ' + store.address) : '' )}}</p>
                        <p @click="moreStoresVisible = !moreStoresVisible">Ver mais</p>
                    </div>
                    <div v-else>
                        <p class="existing-store" v-for="store in product.stores" :key="store.id">{{ store.name + (store.address ? (' - ' + store.address) : '' )}}</p>
                        <p @click="moreStoresVisible = !moreStoresVisible">Ver menos</p>
                    </div> -->
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="share">
                <ShareNetwork
                    network="facebook"
                    url="https://mundovegano.test/artigos/215"
                    :title="product.name"
                    description="Mundo Vegano"
                    quote="Mundo Vegano"
                    hashtags="mundovegano,veganismo"
                >
                    <i class="fa fa-facebook-official"></i> 
                </ShareNetwork>
            </div>
            <div class="share twitter">
                <ShareNetwork
                    network="twitter"
                    url="https://mundovegano.test/artigos/215"
                    :title="product.name"
                    hashtags="mundovegano,veganismo"
                >
                    <i class="fa fa-twitter"></i> 
                </ShareNetwork>
            </div>
            <div class="share whatsapp">
                <ShareNetwork
                    network="whatsapp"
                    url="https://mundovegano.test/artigos/215"
                    :title="product.name"
                    description="Mundo Vegano"
                    hashtags="mundovegano,veganismo"
                >
                    <i class="fa fa-whatsapp"></i> 
                </ShareNetwork>
            </div>

            <!-- Add Stores -->
            <!-- <div v-if="addingStore">
                <label for="brand">Onde encontrou?<i class="fa fa-asterisk mandatory-label"></i></label>
                <div class="form-group row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="text" autocomplete="off" id="store" @keyup="fetchStore" v-model="store" class="form-control input-lg" placeholder="Nome da Loja" />
                            <span class="input-group-addon">-</span>
                            <input type="text" autocomplete="off" id="price" v-model="price" class="form-control input-lg" placeholder="Preço" />
                        </div>
                        <div id="storeList" class="suggestion-list"></div>
                        <input type="hidden" name="stores" id="stores" />
                    </div>       
                    <div class="col-md-2">
                        <button type="button" id="add-store" @click="addStore" class="btn btn-primary" style="height: 47px; cursor: pointer">Adicionar</button>
                    </div>       
                </div>
            </div> -->

            <!-- Tags -->
            <div class="tag-area" v-if="product.tags.length">
                <h2>Tags</h2>
                <h2><span class="badge badge-secondary" style="margin-right: 5px" v-for="tag in product.tags" :key="tag.id">
                    <router-link :to="`/tags/${tag.id}`">{{ tag.name }}</router-link>
                    </span></h2>
            </div>
            
            <!-- Similar Products -->
            <div v-if="product.similarProducts.length" class="similar-area">
                <h2>Similares</h2>
                <div class="row">
                    <product v-for="similar in product.similarProducts" :key="similar.id" :prod="similar" :date_only="true"></product>
                </div>
            </div>

            <!-- Comment Section -->
            <div class="comment-area">
                <h2>Avaliações</h2>
                <div v-if="product.commentCount" class="comment-list">
                    <div class="well well-lg" v-for="review in visibleReviews" :key="review.id">
                        <h4 class="media-heading text-uppercase reviews">{{ review.user == null ? 'Utilizador anónimo' : review.user.name }} </h4>
                        <rating :score="review.score" small="true" :idKey="review.id"></rating>
                        <h6>{{ review.created_at }}</h6>
                        <div class="media-comment">
                            {{ review.comment }}
                        </div>
                    </div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li v-if="reviewCurrentPage > 1" class="page-item"><a class="page-link" href="#!" @click="reviewCurrentPage--">Anterior</a></li>
                            <template v-if="reviewPageCount > 1">
                                <li class="page-item" v-for="page in reviewPageCount" :class="[{ active: page == reviewCurrentPage}]" :key="page"><a class="page-link" href="#!" @click="reviewCurrentPage = page">{{ page }}</a></li>
                            </template>
                            <li v-if="reviewPageCount > 1 && reviewPageCount > reviewCurrentPage" class="page-item"><a class="page-link" href="#!" @click="reviewCurrentPage++">Próxima</a></li>
                        </ul>
                    </nav>
                </div>
                <div v-else>Produto ainda sem avaliações</div>
            </div>

            <form class="comment-form" @submit.prevent="review">
                <div class="review-area" v-if="!this.currentReview">
                    <h2>Deixa uma avaliação</h2>
                    <template v-if="!productReported">
                        <small><a href="#!" data-toggle="modal" data-target="#confirmReport">Denuncie artigo não vegano</a></small>
                    </template>
                    <template v-else>
                        <small v-if="isLogged">Obrigado pela contribuição. O produto será analisado pelos administradores.</small>
                    </template>
                    <template v-if="!productReported">
                        <div v-if="isLogged">Sessão iniciada como {{ user.name }}. <router-link to="/login">Iniciar sessão com utilizador diferente?</router-link></div>
                        <div v-else>Não tem sessão iniciada. <router-link to="/login">Iniciar sessão para comentar?</router-link></div>
                        <div v-if="isLogged">
                            <label class="first-label">Avalia o produto</label>
                            <rating idKey="comment_score"></rating>
                            <label>Comentário</label>
                            <textarea name="comment" v-model="comment"></textarea>
                    </div>
                    </template>
                </div>
                <div class="review-area" v-if="this.currentReview && !isReviewed">
                    <h2>Edite a sua Avaliação</h2>
                    <div v-if="isLogged">Sessão iniciada como {{ user.name }}. <router-link to="/login">Iniciar sessão com utilizador diferente?</router-link></div>
                    <div v-else>Não tem sessão iniciada. <router-link to="/login">Iniciar sessão para comentar?</router-link></div>
                    <div>
                        <label class="first-label">Pontuação</label>
                        <rating idKey="comment_edit_score" :score="parseFloat(currentReview.score)" :edit="true"></rating>
                        <label>Comentário</label>
                        <textarea name="comment" v-model="comment"></textarea>
                    </div>
                </div>
                <div v-if="isLogged && !isReviewed && !currentReview && !productReported" class="button-area"><input type="submit" value="Enviar"></div>
                <div v-if="!isReviewed && currentReview && !productReported" class="button-area"><input type="submit" value="Atualizar"></div>
                <div v-if="reviewMessage" class="reviewAlert alert alert-success" :class="[{ 'alert-success': reviewSuccess, 'alert-danger': !reviewSuccess}]">
                    {{ reviewMessage }}
                </div>
            </form>
                    
        </div>

        <div v-else class="main-content col-md-8">
            A carregar produto...
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar hidden-xs hidden-sm col-md-4">

            <!-- Search Input -->
            <!-- <div class="input-container">
                <input class="input-field" type="text" placeholder="Artigo ou marca" name="search">
                <i class="fa fa-search icon"></i>
            </div> -->

            <!-- Category List -->
            <sidebar :categories="categories"></sidebar>
            <!-- <ul style="padding-left: 0">
                CATEGORIAS 
                <listCategory v-for="cat in categories" :key="cat.id" :cat="cat"></listCategory>         
            </ul> -->


        </div>
            
       <!-- Modal Confirm New Store -->
        <div id="storeModal" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Onde encontraste?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">                      
                            <label for="brand">Distrito</label>
                            <select name="distrito_search" id="distrito_search" class="form-control input-lg" @change="updateConcelho" required="required" data-desc="distrito">
                                <option value="">Selecione</option>                    
                                <option v-for="distrito in distritoList" :key="distrito.name" :value="distrito.distrito">{{ distrito.distrito }}</option>
                            </select>                                           
                        </div>
                        <div class="form-group">                      
                            <label for="concelho">Concelho</label>
                            <select name="concelho_search" id="concelho_search" class="form-control input-lg" @change="updateFreguesia" required="required" data-desc="concelho">
                                <option value="">Selecione</option>                    
                                <option v-for="concelho in concelhoList" :key="concelho.name" :value="concelho.concelho">{{ concelho.concelho }}</option>
                            </select>                                           
                        </div>
                        <div class="form-group">                      
                            <label for="freguesia">Freguesia</label>
                            <select name="freguesia_search" id="freguesia_search" @change="updateLocal" class="form-control input-lg" required="required" data-desc="freguesia">
                                <option value="">Selecione</option>                    
                                <option v-for="freguesia in freguesiaList" :key="freguesia.name" :value="freguesia.freguesia">{{ freguesia.freguesia }}</option>
                            </select>                                           
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" autocomplete="off" id="store" @keyup="fetchStore" v-model="store" class="form-control input-lg" placeholder="Nome da Loja" />
                            <div id="storeList" class="suggestion-list"></div>
                            <input type="hidden" name="stores" id="stores" />     
                        </div>
                        <div class="form-group">
                            <label for="preço">Preço</label>
                            <input type="text" autocomplete="off" id="price" v-model="price" class="form-control input-lg price" placeholder="Preço" stye="margin-top: 15px" /> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" @click="addStore">Confirmar</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
            
       <!-- Modal Confirm New Store -->
        <div id="confirmStore" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Loja Inexistente</h4>
                </div>
                <div class="modal-body">
                    <p>A loja não está presente na nossa base de dados. Confirma pedido de adicionar a loja {{ store }} ?</p>                   
                    
                </div>
                <div class="modal-footer">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" @click="addStore">Confirmar</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
            
       <!-- Modal Confirm Report Product -->
        <div id="confirmReport" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Produto Não Vegano</h4>
                </div>
                <div class="modal-body">
                    <p>Confirma que o produto não é vegano?</p>
                    
                    <div class="form-group">                      
                        <label for="reportText">Observações</label>
                        <textarea name="reportText" id="reportText" v-model="reportText"></textarea>                                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" @click="confirmReport">Confirmar</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
            
       <!-- Modal Store Details -->
        <div id="storeDetails" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ currentStore.name }}</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">                      
                        <label for="brand">Localização</label>
                        {{ (currentStore.address ? currentStore.address + " - " : "") + (typeof currentStore.caop != "undefined" ? currentStore.caop.freguesia : "") }}                            
                    </div>
                    
                    <div class="map" v-if="currentStore.text_location!=''">
                        <div id="osm_map" style="height: 400px; width: 100%"></div>
                    </div>
                    <div v-else>
                        Ainda não existe localização geográfica para esta loja.
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
        
    </div>
</template>

<script>
    import Product from './inc/ProductComponent.vue';
    import Rating from './inc/RatingComponent.vue';
    import SideBar from './inc/SideBarComponent.vue';
    // import ListCategory from './inc/ListCategoryComponent.vue';
    import utils from '../utils';

    export default {
        data() {
            return {
               // comment: '',
                map: null,
                reviewCurrentPage: 1,
                reviewsPerPage: 5,
                comment: this.$store.getters.currentReview ? this.$store.getters.currentReview.comment : '',
                addingStore: false,
                validStore: false,
                confirmNewStore: false,
                store: '',
                currentStore: '',
                price: '',
                freguesiaList: [],
                concelhoList: [],
                distritoList: [],
                reportText: '',
                productReported: false,
                moreStoresVisible: false,
                concelhoUpdate: '',
                freguesiaUpdate: '',
                newStoreAdded: false,
                newStoreError: false
            }
        },
        computed: {
            product() {
                return this.$store.getters.currentProduct;
            },
            filteredStores() {
                let stores = this.$store.getters.currentProduct.stores.filter(function(el) { return el.status != 1; }); 
                return stores;
            },
            filteredStoreChunks() {
                return this.filteredStores.slice(0, 3);
            },
            visibleReviews() {
                let endReview = this.reviewCurrentPage * this.reviewsPerPage;
                let firstReview = endReview - this.reviewsPerPage;
                return this.$store.getters.currentProduct.reviews.slice(firstReview, endReview);
            },
            reviewPageCount() {
                return Math.ceil(this.$store.getters.currentProduct.reviews.length / this.reviewsPerPage);
            },
            currentReview() {
                return this.$store.getters.currentReview;
            },
            isLogged() {
                return this.$store.getters.isLogged;
            },
            user() {
                return this.$store.getters.user;
            },
            isReviewed() {
                return this.$store.getters.isReviewed;
            },
            reviewMessage() {
                return this.$store.getters.reviewMessage;
            },
            reviewSuccess() {
                return this.$store.getters.reviewSuccess;
            },
            categories() {
                return this.$store.getters.mainCategories;
            },
            currentReview() {
                return this.$store.getters.currentReview;
            }
        },
        watch: {
            '$route.params.id': function (id) {
                this.$store.dispatch('fetchProduct', this.$route.params.id);
                this.$store.dispatch('resetReview');
            },
            '$store.getters.currentReview': function (id) {
                this.comment = this.$store.getters.currentReview ? this.$store.getters.currentReview.comment : ''
            },
            '$store.getters.freguesias': function (id) {
                this.freguesiaList = this.$store.getters.freguesias
            },
            '$store.getters.concelhos': function (id) {
                this.concelhoList = this.$store.getters.concelhos
            },
            '$store.getters.distritos': function (id) {
                this.distritoList = this.$store.getters.distritos
            },
            concelhoList: function(val) {
                if(this.concelhoUpdate) {
                    this.$nextTick(() => {
                  
                        $('#concelho_search').val(this.concelhoUpdate);
                        $('#concelho_search').change();
                        if(this.freguesiaUpdate) {
                            this.updateSetFreguesia(this.concelhoUpdate, this.freguesiaUpdate);
                        }
                        this.concelhoUpdate = '';
                        
                    });
                }
            },
            freguesiaList: function(val) {
                if(this.freguesiaUpdate) {
                    this.$nextTick(() => {
                        console.log("DONEEE");
                        $('#freguesia_search').val(this.freguesiaUpdate);
                        this.freguesiaUpdate = '';
                    });
                }
            }
        },
        methods: {
            openStore(storeid) {

                this.currentStore = this.product.stores.find(obj => obj.id == storeid);
                $('#storeDetails').modal("show");
                
                if(this.map!=null) {
                    this.map.setTarget(null);
                    this.map = null;
                }
                this.map = window.olMap.display();
                console.log(this.currentStore);
                var layer = window.olMap.addLayer('stores', this.map, this.currentStore.text_location.replace('POINT','').replace('(','').replace(')','').split(" "));
                
            },
            updateConcelho(e) {
                this.$store.dispatch('fetchConcelhos', $(e.currentTarget).val());
                this.store = '';
                this.validStore = false; 
            },
            updateSetConcelho(distrito, concelho, freguesia) {
                let data = { "distrito": distrito, "concelho": concelho, "el": $("concelho_search")};
                this.concelhoUpdate = concelho;
                this.freguesiaUpdate = freguesia;
                this.$store.dispatch('fetchSetConcelhos', data);
                // this.store = '';
                // this.validStore = false; 
            },
            updateSetFreguesia(concelho, freguesia) {
                let data = { "concelho": concelho, "freguesia": freguesia, "el": $("freguesia_search")};
                this.freguesiaUpdate = freguesia;
                console.log("Freguesia: " + freguesia);
                this.$store.dispatch('fetchSetFreguesias', data);
                // this.store = '';
                // this.validStore = false; 
            },            
            updateFreguesia(e) {
                this.$store.dispatch('fetchFreguesias', $(e.currentTarget).val());
                this.store = '';
                this.validStore = false; 
            },
            updateLocal(e) {
                this.store = '';
                this.validStore = false; 
            },
            groupArrayOfObjects(list, key) {
                return list.reduce(function(rv, x) {
                    (rv[x[key]] = rv[x[key]] || []).push(x);
                    return rv;
                }, {});
            },
            // confirmStore() {
            //     if($('#freguesia').val() == "") {
            //         utils.addVueFlash("danger", '#confirmStore .modal-body', 'Deve preencher freguesia');
            //         return;
            //     }
            //     this.validStore = true;
            //     this.addStore();
            // },
            confirmReport() {

                let that = this;
 
                // REPORT PRODUCT
                axios.post('/api/report-product', { product: this.product.id, message: this.reportText })
                .then(res => {
                    
                    this.productReported = true;
                    $('#reportText').val("");
                    $('#confirmReport').modal("hide");
                    $('.modal-backdrop').remove();

                    if(res.data.error) {
                        utils.addVueFlash("danger", '.subpage', res.data.message);
                    } 
                })
                .catch(error => {
                    utils.addVueFlash("danger", ".subpage", 'Erro ao submeter.');
                    $('#confirmReport').modal("hide");
                    $('.modal-backdrop').remove();
                });


            },
            review() {
                let reviewMethod = this.user == null ? 'reviewGuest' : 'reviewProduct';
                this.$store
                .dispatch(reviewMethod, {
                    rating: $('.comment-form .rating .glyphicon-star.selected').length,
                    comment: this.comment,
                    productId: this.$route.params.id
                })
                .then(res => {
                    //console.log("Comment Registered");
                })
                .catch(err => {
                    utils.addVueFlash("danger", ".subpage", 'Erro ao submeter avaliação.');
                })
            },
            changeImage(e) {
                $('#main-image').prop('src', e.currentTarget.getAttribute('src'));
            },
            filterStores(storeList) {

                        
                var filterDistrito = $('#distrito_search').val();
                var filterConcelho = $('#concelho_search').val();
                var filterFreguesia = $('#freguesia_search').val();
                var filteredStoreList = [];

                for(var i = 0; i < storeList.length; i++) {
                    
                    if(filterFreguesia) {
                        if(storeList[i].caop.freguesia == filterFreguesia
                        && storeList[i].caop.concelho == filterConcelho
                        && storeList[i].caop.distrito == filterDistrito) {
                            filteredStoreList.push(storeList[i]);
                        }
                    } else if(filterConcelho) {
                        if(storeList[i].caop.concelho == filterConcelho
                        && storeList[i].caop.distrito == filterDistrito) {
                            filteredStoreList.push(storeList[i]);
                        }
                    } else if(filterDistrito) {
                        if(storeList[i].caop.distrito == filterDistrito) {
                            filteredStoreList.push(storeList[i]);
                        }
                    } else {
                        filteredStoreList.push(storeList[i]);
                    }
                }

                return filteredStoreList;

            },
            fetchStore() {
                let that = this;
                /* Autocomplete Store */                
                that.validStore = false;
              
                if(that.store != '')
                {
                    axios.get('/api/stores/fetch/' + this.store)
                    .then(res => {
                        // var storeList = res.data.data;
                        
                        // Exclude added or repeated stores
                        var filteredStoreList = that.filterStores(res.data.data);                   

                        let suggestionCount = 0;
                        let suggestionList = '<ul class="dropdown-menu" style="display:block; position:relative">';
                   
                        $(filteredStoreList).each(function(i, val){
                            
                            if(!$('.store-detail:contains("' + val.name + '")').length) {
             
                                suggestionCount = suggestionCount + 1;
                                suggestionList += '<li class="store-suggestion"><a href="#!">\
                                <div class="store-name" distrito="' + val.caop.distrito + '" concelho="' + val.caop.concelho + '" freguesia="' + val.caop.freguesia + '">' + val.name + '</div>\
                                <div>' + val.caop.concelho + '</div></a></li>';  
                            }                 
                        
                        }); 

                        suggestionList += '</ul>';

                        if(suggestionCount) {
                            $('#storeList').fadeIn();  
                            $('#storeList').html(suggestionList);

                            $('li.store-suggestion').click(function(e) {
                                that.validStore = true; 
                                let storeEl = $(this).find('.store-name');
                                let newStore = storeEl.text();
                                $('#distrito_search').val(storeEl.attr('distrito'));
                                that.updateSetConcelho(storeEl.attr('distrito'), storeEl.attr('concelho'), storeEl.attr('freguesia'));
                                $('#store').val(newStore);  
                                that.store = newStore;
                                $('#storeList').fadeOut();  
                            });
                        }

                    })
                    .catch(error => {
                        console.log(error);
                    });
                   
                }
            },   
            addStore: function() {

                let currentStore = $('#store').val();
                let currentPrice = $('#price').val().replaceAll(',', '');
                let currentDistrito = $('#distrito_search').val();
                let currentConcelho = $('#concelho_search').val();
                let currentFreguesia = $('#freguesia_search').val();
                let that = this;
                console.log("Entered");
                console.log(currentPrice);

                if(isNaN(currentPrice)) {
                    utils.addVueFlash("danger", ".subpage", 'Preço inválido');
                    return;
                }

                if(currentStore == '') {
                    utils.addVueFlash("danger", '#storeModal .modal-body', 'Deve preencher loja');
                    return;
                }

                if(currentPrice == '') {
                    utils.addVueFlash("danger", '#storeModal .modal-body', 'Deve preencher preço');
                    return;
                }

                if(currentFreguesia == '') {
                    utils.addVueFlash("danger", '#storeModal .modal-body', 'Deve preencher freguesia');
                    return;
                }

                if(that.validStore) {  

                    // ADD STORE
                    that.insertStore();                    
                    that.validStore = false;

                } else {

                    if(that.confirmNewStore) {

                        that.confirmNewStore = false;
                        that.insertStore(); 
                        that.validStore = false; 

                    } else {
                        
                        that.confirmNewStore = true;

                        $('#storeModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#confirmStore').modal('show');

                    }
                }

            }, 
            async insertStore() {
                let currentStore = $('#store').val();
                let currentPrice = $('#price').val().replaceAll(',', '');
                let currentDistrito = $('#distrito_search').val();
                let currentConcelho = $('#concelho_search').val();
                let currentFreguesia = $('#freguesia_search').val();
                let that = this;

                // ADD STORE
                try {
                    
                    const res = await axios.post('/api/stores', { name: currentStore, price: currentPrice, product: that.product.id, freguesia: currentFreguesia })

                    if(typeof res.data != 'undefined' && typeof res.data.error != 'undefined' && !res.data.error) {
                        
                        that.newStoreAdded = true;
                        that.newStoreError = false;
                        $('#confirmStore').modal('hide');
                        utils.addVueFlash("success", '.subpage', "Muito obrigado! A nossa equipa irá analisar a nova loja e associar a este produto.");
                        //$('.existing-store').after('<p>' + currentStore + '</p>');
                        
                    }
                    $('#store').val("");

                } catch (e) {
                    that.newStoreError = true;
                    if (e.response) {
                        if (e.response.status == 451) {
                            console.log("Loja repetida!");
                            utils.addVueFlash("danger", "#confirmStore .modal-body", 'Já existe uma loja com este nome.');
                        } 
                    }

                    return false; 
                }

                // axios.post('/api/stores', { name: currentStore, price: currentPrice, product: that.product.id, freguesia: currentFreguesia })
                // .then(res => {
                //     if(typeof res.data != 'undefined' && typeof res.data.error != 'undefined' && !res.data.error) {
                //         $('.existing-store').after('<p>' + currentStore + '</p>');
                //     }
                //     $('#store').val("");
                // })
                // .error(error => {
                //     console.log(error);
                // });
            },
            removeStore(el, currentStore) {

                let storeArrayField = $('#stores');
                let storeTable = $('#store-table');

                // Remove store from table
                $(el).parents('tr').remove();
                // Remove store from hidden field
                var storeList = storeArrayField.val().split(";");

                storeList = storeList.filter(function(value, index, arr){ return value != currentStore;});

                storeArrayField.val(storeList.join(";"));
                if(!storeArrayField.val()) {
                        // Hide results table
                        storeTable.parents('.add-record-table').hide();
                }
            }, 
            tableContains: function(tableObj, fieldText) {
                
                var textFound = false;
                var fields = tableObj.find('td').each(function(i, el){
                    if($(el).text() == fieldText) {
                        textFound = true;
                        return textFound;
                    }
                });

                return textFound;
            }
        },
        components: {
            product: Product,
            sidebar: SideBar,
            rating: Rating
            // listCategory: ListCategory
        },
        created() {
            
            $('body').click(function(evt){    
    
                if($(evt.target).hasClass('suggestion')){
                    return;
                }        
        
                $('#brandList').fadeOut(); 
                $('#storeList').fadeOut();  
        
            });

            if(this.$route.params.id) {
                this.$store.dispatch('fetchProduct', this.$route.params.id);
                this.$store.dispatch('fetchReview', this.$route.params.id);
            }

            if(this.$route.params.name) {
                this.$store.dispatch('fetchProductByName', this.$route.params.name);
                this.$store.dispatch('fetchReviewByName', this.$route.params.name);
            }
            
            this.$store.dispatch('fetchDistritos');



        }
    }
   
</script>