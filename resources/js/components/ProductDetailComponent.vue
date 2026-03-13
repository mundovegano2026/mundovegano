<template>
    <div class="row page-content">

        <div v-if="product.name!=''" class="main-content col-md-8">

            <!-- Category Link Badges -->
            <div v-if="product.category" class="path-container">
                <template v-if="product.category.path!=''">
                    <span class="badge badge-primary" style="margin-right: 5px" v-for="cat in product.category.path.split('/')" :key="cat">
                        <router-link :to="`/categorias-nome/${cat}`">{{ cat }}</router-link>
                    </span>
                </template>
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
            <div>
                <div class="image-wrapper">
                    <div class="main-image-container">
                        <img :src="'/' + product.image" id="main-image" style="max-width: 100%">
                    </div>
                </div>
                <div class="image-carousel row" v-if="product.images.length > 1">
                    <div class="col-md-3" v-for="image in product.images" :key="image.id">
                        <img class="image-carousel-img" @click="changeImage" :src="'/storage/product_images/' + image.path">
                    </div>
                </div>
            </div>

            <hr>
            
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

            <figure class="tabBlock">
                <ul class="tabBlock-tabs">
                    <li
                        v-for="(tab, index) in tabs"
                        :key="index"
                        :aria-setsize="tabs.length"
                        :aria-posinet="index + 1"
                    >
                        <a
                        href="javascript:;"
                        class="tabBlock-tab"
                        :class="active_tab === index ? 'is-active' : ''"
                        :aria-current="active_tab === index"
                        @click="changeTab(index)"
                        >
                        <i v-if="index==0" id="icon-shops" class="fa fa-shopping-cart"></i>
                        <i v-if="index==1" id="icon-shops" class="fa fa-info"></i>
                        <i v-if="index==2" id="icon-shops" class="fa fa-comments"></i>
                        <span style="margin-left: 10px">{{ tab.tab_title }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tabBlock-content">
                    <div
                    v-for="(tab, index) in tabs"
                    :key="index"
                    :aria-current="active_tab === index"
                    class="tabBlock-pane"
                    v-show="active_tab === index"
                >
                        <div v-if="index==0">

                            <!-- Stores -->
                            <h1 style="margin: 0 !important">Onde Comprar?</h1> 
                            <span v-if="filteredStoreChunks.length"><a href="#!" class="small-link" data-toggle="modal" data-target="#storeModal">Encontrei noutra loja</a></span>
                            <span v-else>Ainda não existem lojas para este produto. Sê o primeiro a adicionar uma<a href="#!" class="small-link" data-toggle="modal" data-target="#storeModal">AQUI</a>!</span>
                            <div v-if="!moreStoresVisible">
                                <div class="list-group">
                                    <span v-for="store in filteredStoreChunks" :storeId="store.id" :key="store.id">
                                        <a href="#!" @click="openStore(store.id)" class="list-group-item list-group-item-action flex-column align-items-start" >
                                            <div class="row" style="height: 55px">
                                                <div class="col-xs-9 col-md-9">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h4 class="store-detail">{{ store.name }}</h4>
                                                        <!-- <small class="text-muted">{{ store.address }}</small> -->
                                                    </div>
                                                </div>
                                                <div v-if="store.pivot && store.pivot.price!=''" class="col-xs-3 col-md-3" style="text-align: right">
                                                    <h4>
                                                        {{ store.pivot.price != 0 ? store.pivot.price + "€" : "" }}
                                                    </h4>
                                                    
                                                </div>
                                            </div>
                                        </a>
                                    </span>
                                </div>
                                <!-- <h3 style="text-align: center"><p v-if="product.stores.length>maxStoresVisible" class="link-click" @click="moreStoresVisible = !moreStoresVisible">Carregar Mais 10</p></h3> -->
                                <h5 style="text-align: center; text-decoration: underline"><p v-if="product.stores.length>maxStoresVisible" class="link-click" @click="maxStoresVisible+=10">Carregar Mais 10</p></h5>
                            </div>
                            <!-- <div v-else>
                                <div class="list-group">
                                    <a href="#!" @click="openStore(store.id)" class="list-group-item list-group-item-action flex-column align-items-start"  v-for="store in filteredStores" :key="store.id">
                                        <div class="d-flex w-100 justify-content-between">
                                        <h4 class="store-detail">{{ store.name }}</h4>
                                        </div>

                                    </a>
                                </div>
                                <p class="link-click" @click="moreStoresVisible = !moreStoresVisible">Ver menos</p>
                            </div> -->
                                    
                             
                             
                        </div>
                        <div v-if="index==1">
                            <!-- Product Details -->            

                            <!-- Brand -->
                            <p v-if="product.brand"><strong>MARCA:</strong> {{ product.brand.name }}</p>
                            <!-- <div class="product-detail-area" v-if="product.obs">
                                <template v-for="(line, index) in product.obs.split('\n')" >{{line}}<br :key="index"></template>
                            </div> -->
                            <hr v-if="product.brand">
                            <div class="product-detail-area" v-html="product.obs">
                            </div>
                            <div v-if="!product.brand && product.obs == ''">
                                Produto ainda sem detalhes. Podes acrescentar informação <a href="#!" style="color:#6cc091" @click="addDetails">aqui</a>.
                            </div>
                        </div>
                        <div v-if="index==2">
      
                            <!-- Comment Section -->
                            <div class="comment-area">
                                <!-- <h2>Avaliações</h2> -->
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
                        </div>
                    </div>
                </div>
            </figure>

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


            <form class="comment-form" @submit.prevent="review">
                <div class="review-area" v-if="!this.currentReview">
                    <h2>Deixa uma avaliação</h2>
                    <template v-if="!productReported">
                        <small><a href="#!" data-toggle="modal" data-target="#confirmReport">Reportar erro / Acrescentar informação</a></small>
                    </template>
                    <template v-else>
                        <small v-if="isLogged">Obrigado pela contribuição. O produto será analisado pelos administradores.</small>
                    </template>
                    <template>
                        <div v-if="isLogged">Sessão iniciada como {{ user.name }}. <router-link to="/login">Iniciar sessão com utilizador diferente?</router-link></div>
                        <div v-else>Não tem sessão iniciada. <router-link :to="'/login?prod='+product.id">Iniciar sessão para comentar?</router-link></div>
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
                    <div v-else>Não tem sessão iniciada. <router-link :to="'/login?prod='+product.id">Iniciar sessão para comentar?</router-link></div>
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
            
        <div id="overlay" style="display:none;">
            <div class="spinner"></div>
            <br/>
            A Carregar...
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
                        <!-- <div class="form-group">                      
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
                        </div> -->
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6">
                            <button :disabled="store==''" type="button" class="btn btn-default" @click="addStore">Confirmar</button>
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
                    <h4 class="modal-title">Adiciona Nova Loja ao Mundo Vegano</h4>
                </div>
                <div class="modal-body">
                    <p>A loja não está presente na nossa base de dados. Adiciona a localização da loja {{ store }}</p>  
                    <input type="hidden" v-model="store_location" name="store_location" id="store_location" />
                    <div class="map">
                        <div id="osm_store_map" style="height: 400px; width: 100%"></div>
                    </div>
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
                        <select v-model="freguesia_box" name="freguesia_search" id="freguesia_search" @change="updateLocal" class="form-control input-lg" required="required" data-desc="freguesia">
                            <option value="">Selecione</option>                    
                            <option v-for="freguesia in freguesiaList" :key="freguesia.name" :value="freguesia.freguesia">{{ freguesia.freguesia }}</option>
                        </select>                                           
                    </div>   
                    <div class="form-group">                      
                        <label for="address">Morada</label>
                        <input type="text" autocomplete="off" id="address" @keyup="fetchStore" v-model="address" class="form-control input-lg" placeholder="Morada da Loja" />                            
                    </div>                
                    
                </div>
                <div class="modal-footer">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <button :disabled="freguesia_box==''" type="button" class="btn btn-default" @click="addStore">Confirmar</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
            
       <!-- Modal Edit Price -->
        <div id="editPrice" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Corrija o preço nesta loja</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="preço">Novo Preço</label>
                        <input type="text" autocomplete="off" id="newStorePrice" v-model="newStorePrice" class="form-control input-lg price" placeholder="Novo Preço" stye="margin-top: 15px" /> 
                    </div>               
                    
                </div>
                <div class="modal-footer">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" @click="updatePrice">Confirmar</button>
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
                    <h4 class="modal-title">Reportar erro / Nova Informação</h4>
                </div>
                <div class="modal-body">
                    <p>Que situação queres reportar?</p>

                    <div class="form-group">                      
                        <label for="report_type">Tipo<i class="fa fa-asterisk mandatory-label"></i></label>
                        <select name="report_type" id="report_type" class="form-control input-lg" required="required" data-desc="Tipo" v-model="reportType">
                            <option value="">Selecione</option>                    
                            <option v-for="type in reportTypeList" :key="type.id" :value="type.id">{{ type.type }}</option>
                        </select>                                 
                    </div>
                    <div class="form-group">                      
                        <label for="reportText" style="margin-bottom: 0">Observações</label>
                        <div style="margin-bottom: 1em"><small>- Explica, por favor, o erro existente na informação e/ou acrescenta mais informações que tenhas sobre o produto -</small></div>
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

                    <div v-if="currentStore.pivot" class="form-group">        
                        <p>
                            <small v-if="currentStore.chain_id > 0"><i>Esta cadeia comercializa este produto. Caso o produto não esteja disponível nesta loja, solicita junto do gerente da loja para que passe a estar. Se mesmo assim o produto continuar sem estar disponível, por favor, <a href="#!" @click="wrongStore">informa-nos</a>.</i></small>              
                        </p>
                        <label for="brand">Preço do Produto</label>
                        {{ currentStore.pivot != null && currentStore.pivot.price > 0 ? currentStore.pivot.price + "€" : "Ainda não existe indicação de preço" }}          
                        <i class="fa fa-pencil" style="cursor: pointer; font-size: 21px; margin-left: 5px" @click.prevent="toggleEditPrice" aria-hidden="true" title="Atualizar Preço"></i>                    
                    </div>
                                                        
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
    import init from "./../init.js";

    export default {
        data() {
            return {
               // comment: '',    
                location:null,
                address: '',
                store_location:'',
                freguesia_box:'',
                maxStoresVisible: 10,
                gettingLocation: false,
                errorStr:null,
                map: null,
                newmap: null,
                newStorePrice: '',
                reviewCurrentPage: 1,
                reviewsPerPage: 5,
                comment: this.$store.getters.currentReview ? this.$store.getters.currentReview.comment : '',
                addingStore: false,
                validStore: false,
                confirmNewStore: false,
                store: '',
                currentStore: '',
                price: '',
                reportTypeList: [],
                freguesiaList: [],
                concelhoList: [],
                distritoList: [],
                reportText: '',
                reportType: '',
                productReported: false,
                moreStoresVisible: false,
                concelhoUpdate: '',
                freguesiaUpdate: '',
                newStoreAdded: false,
                newStoreError: false,
                active_tab: 0,
                currentStoreIsChain: false,
                tabs: [
                    {
                    tab_title: "Lojas",
                    tab_icon: "shops",
                    tab_content:
                        ""
                    },
                    {
                    tab_title: "Detalhes",
                    tab_icon: "details",
                    tab_content:
                        ""
                    },
                    {
                    tab_title: "Avaliações",
                    tab_icon: "reviews",
                    tab_content:
                        ''
                    }
                ]
            }
        },
        mixins: [init],
        computed: {
            product() {
                return this.$store.getters.currentProduct;
            },
            filteredStores() {
                
                // Remove unvalidated stores
                let stores = this.$store.getters.currentProduct.stores.filter(function(el) { return el.status != 3; }); 
                
                // Group by repeated stores (multiple prices)
                stores = this.groupBy(stores, 'id');
                
                let newArray = [];
                let storesLen = this.maxStoresVisible < stores.length ? this.maxStoresVisible : stores.length;

                // for(var i = 0; i < stores.length; i++) {
                for(var i = 0; i < storesLen; i++) {
                    newArray.push(stores[i][1][stores[i][1].length-1]);
                }
    
                return newArray;
            },
            filteredStoreChunks() {
                return this.filteredStores.slice(0, this.maxStoresVisible);
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
            '$store.getters.reportTypes': function(id) {
                this.reportTypeList = this.$store.getters.reportTypes
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
                        $('#freguesia_search').val(this.freguesiaUpdate);
                        this.freguesiaUpdate = '';
                    });
                }
            }
        },
        methods: {
            groupBy(xs, key) {
                let objArray = xs.reduce(function(rv, x) {
                    (rv[x[key]] = rv[x[key]] || []).push(x);
                    return rv;
                }, {});
                let array = Object.keys(objArray).map(function(key) {
                    return [Number(key), objArray[key]];
                });

                return array.reverse();
            },
            addDetails() {
                $('#confirmReport').modal('show');
            },
            wrongStore() {
                this.reportType = 2;
                this.reportText = "A loja " + this.currentStore.name + " não vende este produto.";
                $('#confirmReport').modal('show');
                $('#storeDetails').modal('hide');
            },
            toggleEditPrice(el) {
                
                $('#storeDetails').modal('hide');
                $('#editPrice').modal('show');
                el.stopPropagation();

            },
            changeTab(tabIndexValue) {

                this.active_tab = tabIndexValue;

            },
            openStore(storeid) {
                
                this.currentStore = this.product.stores.slice().reverse().find(obj => obj.id == storeid);
              
                this.currentStoreIsChain = this.currentStore.chain_id != null;

                $('#storeDetails').modal("show");
                
                if(this.map!=null) {
                    this.map.setTarget(null);
                    this.map = null;
                }
                this.map = window.olMap.display();
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
            updatePrice() {

                let that = this;
                axios.post('/api/update-price', { product: this.product.id, newPrice: $('#newStorePrice').val().replaceAll(',', ''), store: this.currentStore.id }).then(res => {
                    
                    $('#editPrice').modal("hide");
                    $('.modal-backdrop').remove();

                    if(res.data.error) {
                        utils.addVueFlash("danger", '.subpage', res.data.message);
                    } else {
                        utils.addVueFlash("success", '.subpage', "Obrigado por registares o preço mais recente deste produto!");
                        // Add added price to store array
                        this.$store.dispatch('updateCurrentStores', res.data.product.stores);
                        that.newStorePrice = '';
                    }
                }).catch(error => {
                    utils.addVueFlash("danger", ".subpage", 'Erro ao submeter.');
                    $('#editPrice').modal('hide');
                    $('.modal-backdrop').remove();
                });
                
            },
            confirmReport() {

                let that = this;
                $('#overlay').fadeIn();
 
                // REPORT PRODUCT
                axios.post('/api/report-product', { product: this.product.id, message: this.reportText, report_type: this.reportType })
                .then(res => {
                    
                    this.productReported = true;
                    $('#reportText').val("");
                    $('#confirmReport').modal("hide");
                    $('.modal-backdrop').remove();

                    if(res.data.error) {
                        utils.addVueFlash("danger", '.subpage', res.data.message);
                    } else {                        
                        utils.addVueFlash("success", '.subpage', res.data.message);
                    }
                    $('#overlay').fadeOut();
                })
                .catch(error => {
                    utils.addVueFlash("danger", ".subpage", 'Erro ao submeter.');
                    $('#confirmReport').modal("hide");
                    $('.modal-backdrop').remove();
                    $('#overlay').fadeOut();
                });


            },
            review() {
                let reviewMethod = this.user == null ? 'reviewGuest' : 'reviewProduct';
                this.$store
                .dispatch(reviewMethod, {
                    rating: $('.comment-form .rating .glyphicon-star.selected').length,
                    comment: this.comment,
                    productId: this.product.id
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

                if(isNaN(currentPrice)) {
                    utils.addVueFlash("danger", ".subpage", 'Preço inválido');
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

                        if(that.newMap!=null) {
                            that.newMap.setTarget(null);
                            that.newMap = null;
                        }
                        that.newMap = window.olMap.display('osm_store_map');
                        var layer = window.olMap.addLayer('newStore', map);
                        //var layer = window.olMap.addLayer('stores', this.map, this.currentStore.text_location.replace('POINT','').replace('(','').replace(')','').split(" "));
                        that.newMap.addEventListener('click', (event) => ((featureCoordField) => {    
                            that.setCaopFromCoord(event, featureCoordField);
                        })($('#store_location')));

                 
                        if(("geolocation" in navigator)) {

                            // get position
                            navigator.geolocation.getCurrentPosition(pos => {
                                
                                window.currentMap = that.newMap;
                                window.centerFunction = function(){
                                    that.newMap.getView().setCenter(window.olMap.transform([pos.coords.longitude, pos.coords.latitude], 'EPSG:4326', 'EPSG:3763'));
                                    that.newMap.getView().setZoom(13);
                                }
                            }, err => {
                                console.log("Geo Location Error");
                            });
                            
                            $('.ol-zoom-out').after('<button class="ol-center" type="button" title="Center" onClick="window.centerFunction()"><i class="fa fa-crosshairs"></i></button>');

                        }

                        
                    }
                }

            },    

            setCaopFromCoord: function(e, featureCoordField) {

                // Load spinner
                $('#overlay').fadeIn();       
 
                // Clear previously present stores
                this.resetLayer(e, featureCoordField);
                this.store_location = featureCoordField.val();
                let that = this;

                // GET ADDRESS
                // Coords of click is evt.coordinate
                //console.log("evt.coordinate: " + e.coordinate);
                // You must transform the coordinates because evt.coordinate 
                // is by default Web Mercator (EPSG:3857) 
                // and not "usual coords" (EPSG:4326) 
                const coords_click = window.olMap.transform(e.coordinate, 'EPSG:3763', 'EPSG:4326');
                // console.log("Mouse Click coordinates: " + coords_click);

                // MOUSE CLICK: Longitude
                const lon = coords_click[0];
                // MOUSE CLICK: Latitude
                const lat = coords_click[1];
                // DATA to put in NOMINATIM URL to find address of mouse click location
                const data_for_url = {lon: lon, lat: lat, format: "json", limit: 1};

                // ENCODED DATA for URL
                const encoded_data = Object.keys(data_for_url).map(function (k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(data_for_url[k])
                }).join('&');


                // FULL URL for searching address of mouse click
                const url_nominatim = 'https://nominatim.openstreetmap.org/reverse?' + encoded_data;
                $.ajax({
                    type: "GET",
                    url: url_nominatim,
                    success: function (response_text) {

                        // JSON Data of the response to the request Nominatim
                        const data_json = response_text;

                        // All the informations of the address are here
                        const res_address = data_json.address;
                        if(res_address.road != '') {
                            $('#address').val(res_address.road);
                        }

                        
                            // Query new caop
                            $.ajax({
                                type: "GET",
                                url: "/api/getDicofre/" + e.coordinate[0] + "," + e.coordinate[1],
                                contentType: false,
                                processData: false,
                                success: function (response) {

                                    // Update select boxes
                                    that.updateCaop(response);

                                    // Unload spinner
                                    $('#overlay').fadeOut();    
                                    
                                },
                                error: function(xhr, error) {                
                                    // Unload spinner
                                    //$('#overlay').fadeOut();   
                                    utils.addVueFlash("danger", "#confirmStore .modal-body", 'Erro ao obter área administrativa na localização escolhida.');                                    

                                    // Unload spinner
                                    $('#overlay').fadeOut();  
                                }
                            });
                        
                    },
                    error: function(xhr, error) {        
                        
                        // Query new caop
                        $.ajax({
                            type: "GET",
                            url: "/api/getDicofre/" + e.coordinate[0] + "," + e.coordinate[1],
                            contentType: false,
                            processData: false,
                            success: function (response) {

                                // Update select boxes
                                that.updateCaop(response);

                                // Unload spinner
                                $('#overlay').fadeOut();    
                                
                            },
                            error: function(xhr, error) {                
                                // Unload spinner
                                //$('#overlay').fadeOut(); 
                                utils.addVueFlash("danger", "#confirmStore .modal-body", 'Erro ao obter área administrativa na localização escolhida.');                                      
                                
                                // Unload spinner
                                $('#overlay').fadeOut();  
                            }
                        });

                    }
                });

                    
            },

            updateCaop: function(data) {

                let that = this;        
                var distritoSelect = $('#distrito_search');
                var concelhoSelect = $('#concelho_search');
                var freguesiaSelect = $('#freguesia_search');
                var originalDistrito = distritoSelect.val();
                var originalConcelho = concelhoSelect.val();
                var newDistrito = data["dicofre"]["dicofre"].substr(0, 2);
                var newConcelho = data["dicofre"]["dicofre"].substr(0, 4);
                
                var newFreguesia = data["dicofre"]["dicofre"];

                var concelhoList = data["concelhos"];
                var freguesiaList = data["freguesias"];
                
                if(originalDistrito != newDistrito) {

                    let distritoName = data["distritos"][newDistrito];
                    // Set new distrito                        
                    distritoSelect.val(distritoName);

                    // Update Concelhos
                    that.updateCaopSelect(concelhoSelect, newDistrito != "", concelhoList, newConcelho);
                } 

                if(originalConcelho != newConcelho){                            
                    
                    // Update Freguesias
                    that.updateCaopSelect(freguesiaSelect, newConcelho != "", freguesiaList, newFreguesia); 
                    this.freguesia_box = newFreguesia;
                    
                } 
            },

            resetLayer: function(newCoord, featureCoordField) {
            
                window.olMap.mapObj.getLayers().forEach(function (layer) {
                    
                    if (typeof layer.values_ != undefined && layer.values_.title === 'newStore') {
                        console.log("ERASING");
                        layer.getSource().clear();
                        if(newCoord) {
                            window.olMap.addFeature(layer, newCoord.coordinate);
                            featureCoordField.val(newCoord.coordinate);
                        } else {
                            featureCoordField.val("");
                        }
                    }
                });
            },
            updateCaopSelect: function(selectBox, updateOptions, list, selectedValue) {

                selectBox.find('option').not(':first').remove();
                if(updateOptions) {
                    Object.entries(list).forEach(([key, value]) => {           
                        var selected = "";
                        // Set new value    
                        if(key == selectedValue) selected = "selected"; 
                        selectBox.append("<option value=" + key + " " + selected + ">" + value + "</option>");
                    });  
                } 
            }, 
            async insertStore() {

                let currentStore = $('#store').val();
                let currentPrice = $('#price').val().replaceAll(',', '');
                let currentDistrito = $('#distrito_search option:selected').text();
                let currentConcelho = $('#concelho_search option:selected').text();
                let currentFreguesia = $('#freguesia_search option:selected').text();
                let currentStoreLocation = $('#store_location').val();
                let currentStoreAddress = $('#address').val();
                let that = this;

                // ADD STORE
                try {
                    
                    const res = await axios.post('/api/stores', { name: currentStore, price: currentPrice, product: that.product.id, freguesia: currentFreguesia, location: currentStoreLocation, address: currentStoreAddress })

                    if(typeof res.data != 'undefined' && typeof res.data.error != 'undefined' && !res.data.error) {

                        that.newStoreAdded = true;
                        that.newStoreError = false;
                        $('#confirmStore').modal('hide');
                        $('#storeModal').modal('hide');
                        $('.modal-backdrop').remove();
                        utils.addVueFlash("success", '.subpage', "Muito obrigado pela contribuição, este produto já conta com a nova loja!");
                        this.$store.dispatch('setNewProductStore', res.data.store);
                        
                    }
                    $('#store').val("");

                } catch (e) {
                    that.newStoreError = true;
                    if (e.response) {
                        if (e.response.status == 451) {
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
            },
            getProd: function(position) {
                console.log(this.location);
                if(this.$route.params.id) {
                    // this.$store.dispatch('fetchProduct', this.$route.params.id);
                    this.$store.dispatch('fetchProductCoord', { id: this.$route.params.id, pos: position });
                    this.$store.dispatch('fetchReview', this.$route.params.id);
                }

                if(this.$route.params.name) {
                    // this.$store.dispatch('fetchProductByName', this.$route.params.name);
                    this.$store.dispatch('fetchProductByNameCoord', { name: this.$route.params.name, pos: position });
                    this.$store.dispatch('fetchReviewByName', this.$route.params.name);
                }
            }
        },
        components: {
            product: Product,
            sidebar: SideBar,
            rating: Rating
            // listCategory: ListCategory
        },
        mounted()  {
            this.$store.dispatch('resetProduct');
        },
        created() {

            init.methods.initDoc();
            
            $('body').click(function(evt){    
    
                if($(evt.target).hasClass('suggestion')){
                    return;
                }        
        
                $('#brandList').fadeOut(); 
                $('#storeList').fadeOut();  
        
            });
            
            if(("geolocation" in navigator)) {
                this.gettingLocation = true;
                // get position
                navigator.geolocation.getCurrentPosition(pos => {
                    this.gettingLocation = false;
                    this.location = pos;
                    console.log(pos.coords);
                    this.getProd(pos.coords);
                }, err => {
                    console.log("Geo Location Error");
                    this.gettingLocation = false;
                    this.errorStr = err.message;
                });
                
                this.$store.dispatch('fetchDistritos');
                this.$store.dispatch('fetchReportTypes');
            } else {
                this.getProd(null);
            }



        }
    }
   
</script>