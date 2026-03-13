<template>
    <div class="page-content main-content">
          
        <h2 v-if="!productRegistered">Partilha um novo produto</h2>
        
        <!-- Instructions -->
        <div v-if="!productRegistered" class="instructions-area">
            <div class="instructions-paragraph"><strong>Conheces ou encontraste um produto vegano que não consta ainda no Mundo Vegano?</strong> Fornece-nos, por favor, as seguintes informações sobre o produto:</div>
            <div class="instructions-paragraph note">Os campos assinalados com <i class="fa fa-asterisk"></i> são obrigatórios</div>
            <div class="instructions-paragraph note">Se tiveres dúvidas se um produto é vegano consulta o <router-link to="/manual" target="_blank">manual de instruções.</router-link></div>               
        </div>
        <h2 v-if="productRegistered" style="text-align: center">Obrigado por ajudares a construir o Mundo Vegano! </h2>
        <p v-if="productRegistered" style="text-align: center">Se sabes de outro produto vegano que não conste ainda do Mundo Vegano, clica em <router-link to="#" @click.native="resetProduct">partilha novo produto.</router-link></p>


        <form v-if="!productRegistered" class="product-form" type="multipart/form-data" @submit.prevent="submitProduct">  
            
            <!-- Name / Brand -->  
            <div class="form-group">
                <label for="name">Nome e/ou Marca<i class="fa fa-asterisk mandatory-label"></i></label>
                <input type="text" class="form-control input-lg" name="name" id="name" required="required" v-model="newProduct.name">
            </div>

            <!-- Name -->  
            <!-- <div class="form-group">
                <label for="name">Nome<i class="fa fa-asterisk mandatory-label"></i></label>
                <input type="text" class="form-control input-lg" name="name" id="name" v-model="newProduct.name">
            </div> -->

            <!-- Brand -->
            <!-- <div class="form-group">
                <label for="brand">Marca</label>
                <input type="text" class="form-control input-lg" name="brand" id="brand" @keypress="fetchBrand" v-model="newProduct.brand.name" autocomplete="off">
                <div id="brandList" class="suggestion-list"></div>
            </div> -->

            <!-- Category -->
            <div class="form-group">                      
                <label for="brand">Categoria<i class="fa fa-asterisk mandatory-label"></i></label>
                <select name="category" pos="1" class="form-control input-lg category_select" required="required" data-desc="categoria">
                    <option value="">Selecione</option>                    
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>                                           
            </div>

            <!-- Stores -->
              <fieldset>
                <legend>Onde encontraste?<i class="fa fa-asterisk mandatory-label"></i></legend>
                <!-- <label for="brand">Onde encontraste?<i class="fa fa-asterisk mandatory-label"></i> -->
                <p>
                    <small class="form-note">- Regista a loja onde encontraste o produto - </small>
                    <!-- <button v-if="!multiStores" class="btn pull-right" title="Adiciona mais que uma loja" style="width: auto" @click.prevent="multiStores=true"><i class="fa fa-list-ol"></i></button> -->
                </p>
                <!-- </label> -->
                
                <!-- Old display in line  -->
                <!-- <div class="form-group row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="text" autocomplete="off" id="store" @keyup="fetchStore" v-model="store" class="form-control input-lg" placeholder="Nome da Loja" />
                            <span class="input-group-addon">-</span>
                            <input type="text" autocomplete="off" id="price" class="form-control input-lg" placeholder="Preço" />
                        </div>
                        <div id="storeList" class="suggestion-list"></div>
                        <input type="hidden" name="stores" id="stores" />
                    </div>       
                    <div class="col-md-2">
                        <button type="button" id="add-store" @click="addStore" class="btn btn-primary" style="height: 47px; cursor: pointer">Adicionar</button>
                    </div>       
                </div> -->
                <div class="form-group">                      
                    <label for="store">Nome da Loja<i class="fa fa-asterisk mandatory-label"></i></label>
                    <input type="text" autocomplete="off" id="store" @keyup="fetchStore" v-model="store" class="form-control input-lg" placeholder="Nome da Loja" />                                          
                    <div id="storeList" class="suggestion-list"></div>
                    <input type="hidden" name="stores" id="stores" />
                    <label for="price">Preço do Produto</label>
                    <input type="text" autocomplete="off" id="price" class="form-control input-lg price" placeholder="Preço (opcional)" />
                    
                    <button v-if="multiStores" type="button" id="add-store" @click="addStore" class="btn btn-primary" style="height: 47px; cursor: pointer">Salvar Loja</button>
                </div>

                <div class="form-group add-record-table" style="display: none">
                    <table id="store-table" class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col"></th>
                            <th scope="col">Nome</th>
                            <th scope="col">Preço</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                </div>


            </fieldset>



            <!-- Images -->              
            <fieldset>
                <legend>Imagens<i class="fa fa-asterisk mandatory-label"></i></legend>
                <!-- <label for="images" style="margin-top: 15px">Imagens</label> -->
                <p><small class="form-note">- Queres partilhar connosco uma ou mais imagens do novo produto vegano que encontraste? - </small></p>
                <div class="form-group">

                    <div class="image-upload-widget">
                        <img id="output_image" src="" class="output-image">
                        <div class="image-upload-area">
                            <!-- First Image -->
                            <label for="image" class="custom-image-upload">
                                <i class="fa fa-cloud-upload"></i> <span class='label-text'>Carregar Imagem</span>
                            </label>
                            <input id="image" name="image" type="file" ref="file" @change="handleFileUpload" />

                            <!-- Extra Images -->                              
                            <label for="extra_image_1" style="display: none" class="custom-image-upload extra">
                                <i class="fa fa-cloud-upload"></i> <span class='label-text'>Carregar Adicional</span>
                            </label>
                            <input id="extra_image_1" name="extra_photos[]" pos="1" class="extra-image" type="file" />
                        
                        </div>
                        <div class="image-display-area">
                            <div class="row">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Obs -->
            <div class="form-group">
                <label for="obs">Outras Informações</label>
                <!-- <textarea class="form-control" rows="8" name="obs" id="obs" v-model="newProduct.obs"></textarea> -->
                <ckeditor :editor="editor" v-model="newProduct.obs"></ckeditor>
            </div>

            <!-- Tags -->  
            <!-- <div class="form-group">
                <label for="tags">Tags</label>
                <input id="tags" name="tags" v-model="newProduct.tags" value="" placeholder="Adicione as tags relevantes" autocomplete="off">
            </div> -->

            <!-- Avaliação -->  
            <form class="comment-form" @submit.prevent="review">
                <div class="review-area" v-if="!this.currentReview">
                    <h2>Deixa uma avaliação (opcional)</h2>
                    <template v-if="productReported">
                        <small v-if="isLogged">Obrigado pela contribuição. O produto será analisado pelos administradores.</small>
                    </template>
                    <template v-else>
                        <div>
                            <label class="first-label">Avalia o produto</label>
                            <rating idKey="comment_score"></rating>
                            <label>Comentário</label>
                            <textarea name="comment" v-model="newProduct.comment"></textarea>
                    </div>
                    </template>
                </div>
            </form>


            <!-- New user -->  
            <!-- <div v-if="!isLogged" class="form-group">
                <h4>Novo no Mundo Vegano?</h4>
                <div>Indica-nos o teu nome e email se pretendes registar-te no Mundo Vegano para consultar os produtos que submeteste</div>
                <div>Caso já tenhas conta, podes <a href="#!">iniciar sessão aqui.</a></div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="tags">Nome</label>
                        <input id="username" name="username" class="form-control input-lg" type="text" v-model="newUser.name" />
                    </div>
                    <div class="col-md-6">
                        <label for="tags">Email</label>
                        <input id="useremail" name="useremail" class="form-control input-lg" type="email" v-model="newUser.email" />
                    </div>
                </div>
            </div> -->
            <div v-if="!isLogged">Tens conta no Mundo Vegano? <router-link @click.native="saveCurrentRecord" to="/login">inicia a sessão aqui.</router-link> para registar o produto na tua conta.</div>
            <div v-if="!isLogged">Queres juntar-te ao Mundo Vegano primeiro? <router-link @click.native="saveCurrentRecord" to="/registar">Regista-te aqui.</router-link></div>
            <br>
            <button type="submit">PARTILHA PRODUTO<i class="fa fa-paper-plane fa-btn"></i></button>

        </form>
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
                        <button :disabled="freguesia_box==''" type="button" class="btn btn-default" @click="addNewStore">Confirmar</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
                 


       <!-- Modal Confirm New Brand -->
        <div id="confirmBrand" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Marca Inexistente</h4>
                </div>
                <div class="modal-body">
                    <p>Tens a certeza que queres adicionar a nova marca?</p>
                </div>
                <div class="modal-footer row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" @click="confirmBrand" data-dismiss="modal">Confirmar</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
    
    </div>
</template>

<script>
    import axios from 'axios';
    import utils from '../utils';
    import Rating from './inc/RatingComponent.vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import CKEditor from '@ckeditor/ckeditor5-vue2';
    import init from "./../init.js";

    export default {
        data() {
            return {
                newProduct: this.$store.getters.currentProduct,
                validBrand: true,
                validStore: false,
                productReported: false,
                map: null,
                newmap: null,
                store_location:'',
                freguesia_box:'',
                address: '',
                // comment: this.$store.getters.currentReview ? this.$store.getters.currentReview.comment : '',
                store: '',
                tags: [],
                file: '',
                extraFiles: [],
                newUser: {
                    name: '',
                    email: ''
                },
                freguesiaList: [],
                concelhoList: [],
                distritoList: [],
                newStore: '',
                newPrice: '',
                editor: ClassicEditor,
                multiStores: false
            }
        },
        mixins: [init],
        watch: {
            '$store.getters.tags': function() {
                this.tags = this.$store.getters.tags.map(function(a) {return a.name;});
                // Initialize 
                $('#tags').tagator({
                    autocomplete: this.tags
                });                
            },
            // '$store.getters.productRegistered': function() {
            //     console.log("TESTING: " + this.$store.getters.productRegistered);
            //     this.productRegistered = this.$store.getters.productRegistered;             
            // },
            '$store.getters.currentProduct': function() {
                this.newProduct = this.$store.getters.currentProduct;             
            },
            '$store.getters.freguesias': function (id) {
                this.freguesiaList = this.$store.getters.freguesias
            },
            '$store.getters.concelhos': function (id) {
                this.concelhoList = this.$store.getters.concelhos
            },
            '$store.getters.distritos': function (id) {
                this.distritoList = this.$store.getters.distritos
            }
        },
        computed: {
            isLogged() {
                return this.$store.getters.isLogged;
            },
            currentReview() {
                return this.$store.getters.currentReview;
            },
            user() {
                return this.$store.getters.user;
            },
            categories() {
                return this.$store.getters.mainCategories;
            },
            registerMessage() {
                return this.$store.getters.registerMessage;
            },
            productRegistered() {
                return this.$store.getters.productRegistered;
            }
            
        },
        components: {
            rating: Rating,
            ckeditor: CKEditor.component
        },
        methods: {   
            saveCurrentRecord() {
                this.prepareProduct();
                this.$store.dispatch('saveCurrentProduct', this.newProduct);
                this.$store.dispatch('setRegistering');
            },
            confirmBrand() {
                this.validBrand = true;
                this.submitProduct();
            },   
            resetProduct() {
                console.log("Reseting product!");
                this.$store.dispatch('resetProduct');
            },
            prepareStore() {

                // If there isn't a store, do nothing
                if($('#store').val()=="") return false;

                let data = { store: $('#store').val(), price: $('#price').val(), submitting: true };
                
                return this.addStore(data);

            },
            prepareProduct() {

                let that = this;
                this.newProduct.category = $('.category_select').last().val();
                this.newProduct.categoryCascade = [];
                $('.category_select').each(function(i, el) {
                    that.newProduct.categoryCascade.push(el.value);
                });

                this.newProduct.stores = $('#stores').val();
                
                
                var tags = "";
                $('.tagator_tags .tagator_tag').each(function(i, el) {
                    tags += $(el).text().slice(0,-1) + ";";
                });
                this.newProduct.tags = tags;                
                this.newProduct.rating = $('.comment-form .rating .glyphicon-star.selected').length;

            },
            validateProduct() {
                
                let validation = true;

                // Validate Stores
                if(!this.newProduct.stores) {
                    utils.addVueFlash("danger", ".subpage", "Adiciona a loja onde encontraste o produto.");
                    validation = false;
                }

                // Validate Images
                if($('#image').val() == "") {
                    utils.addVueFlash("danger", ".subpage", "Adiciona pelo menos uma imagem do produto.");
                    validation = false;
                }

                return validation;

            },
            resetForm() {
                this.$store.dispatch('setRegistered');
                $('#store').val('');
                this.store = '';
            },
            submitProduct() {
                      
                $('#overlay').fadeIn();

                let that = this;
                
                // If multistore mode is not activated, prepare single store for upload
                if(!this.multiStores) {
                    if(!this.prepareStore()) {  
                        $('#overlay').fadeOut();
                        return;
                    }
                }

                this.prepareProduct();

                // If product isn't complete, stop process
                if(!this.validateProduct()) {
                    $('#overlay').fadeOut();
                    return;
                } 

                const formData = new FormData();
                formData.append('product', JSON.stringify(this.newProduct));
                formData.append('newUser', JSON.stringify(this.newUser));
                // Images
                formData.append('image', this.file); // formData.append('image', image.files[0]);
                $('.extra-image').each(function(i, el) {
                    let inputFileList = el.files;
                    if(inputFileList[0] != 'undefined')
                        formData.append('extra[' + i + ']', inputFileList[0]);
                });

                if(!that.validBrand) {
                    $('#confirmBrand').modal('show');
                    return;
                }

                let requestURL = this.isLogged ? '/api/products' : '/api/productsguest';
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
                            this.resetForm();
                        }
                    } else {
                        utils.addVueFlash("danger", ".subpage", "Erro ao registar produto.");
                    }
                
                    $('#overlay').fadeOut();
                    
                })
                .catch(error => {
                   console.log(error);
                   utils.addVueFlash("danger", ".subpage", "Erro ao registar produto.");
                
                    $('#overlay').fadeOut();
              
                });
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
                this.newProduct.savedImage = this.$refs.file.files[0];
            },
            fetchCurrentStores() {

                let currentStoreValues = $('#stores').val().split(';');
                let currentStores = [];

                for(var i = 0; i < currentStoreValues.length; i++) {
                    currentStores.push(currentStoreValues[i].split('||')[0]);
                }

                return currentStores;

            },
            fetchStore() {
                let that = this;
                /* Autocomplete Store */                
                that.validStore = false;
              
                if(that.store != '')
                {
                    axios.get('/api/stores/fetch/' + this.store)
                    .then(res => {

                        let currentAddedStores = this.fetchCurrentStores();

                        let suggestionList = '<ul class="dropdown-menu" style="display:block; position:relative">';
                        
                        $(res.data.data).each(function(i, val){

                            if(currentAddedStores.indexOf(val.name) === -1) {
                                suggestionList += '<li class="store-suggestion"><a href="#!">\
                                <div class="store-name">' + val.name + '</div>\
                                <div>' + val.caop.concelho + '</div></a></li>';
                            }                   
                        
                        }); 
                        suggestionList += '</ul>';

                        if(res.data.data.length) {
                            $('#storeList').fadeIn();  
                            $('#storeList').html(suggestionList);

                            $('li.store-suggestion').click(function(e) {
                                that.validStore = true; 
                                // $('#store').val($(this).find('.store-name').text());  
                                that.store = $(this).find('.store-name').text();
                                $('#storeList').fadeOut();  
                            });
                        }

                    })
                    .catch(error => {
                        console.log(error);
                    });
                   
                }
            },
            updateConcelho(e) {
                this.$store.dispatch('fetchConcelhos', $(e.currentTarget).val());
                //this.store = '';
                //this.validStore = false; 
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
                //this.store = '';
                //this.validStore = false; 
            },
            updateLocal(e) {
                //this.store = '';
                //this.validStore = false; 
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
            // addNewStore: function() {

            //     if(this.newStore == "") {
            //         utils.addFlash('danger', '#storeModal .modal-body', 'Preenche nome da nova loja.');
            //         return;
            //     }

            //     if($('#freguesia_search').val() == "") {
            //         utils.addFlash('danger', '#storeModal .modal-body', 'Preenche freguesia da nova loja.');
            //         return;
            //     }
                
            //     this.insertStore(this.newStore, this.newPrice);

            //     let data = { store: this.newStore, price: this.newPrice };
                
            //     this.addStore(data);

            //     this.newStore = '';
            //     this.newPrice = '';
            //     $('#distrito_search').val('').trigger('change');
            //     $('#concelho_search').val('').trigger('change');
            //     $('#freguesia_search').val('').trigger('change');
            //     $('#storeModal').modal('toggle');

            // },  
            addNewStore: function() {

                if(this.newStore == "") {
                    utils.addFlash('danger', '#storeModal .modal-body', 'Preenche nome da nova loja.');
                    return;
                }

                if($('#freguesia_search').val() == "") {
                    utils.addFlash('danger', '#storeModal .modal-body', 'Preenche freguesia da nova loja.');
                    return;
                }
                
                this.insertStore(this.newStore, this.newPrice);

                let data = { store: this.newStore, price: this.newPrice };
                
                this.addStore(data);

                this.newStore = '';
                this.newPrice = '';
                $('#distrito_search').val('').trigger('change');
                $('#concelho_search').val('').trigger('change');
                $('#freguesia_search').val('').trigger('change');
                $('#storeModal').modal('toggle');

            }, 
            // insertStore(el, currentStore) {
            async insertStore(newStore, newPrice) {

                let currentStore = newStore;
                let currentPrice = newPrice.replaceAll(',', '');
                let currentDistrito = $('#distrito_search').val();
                let currentConcelho = $('#concelho_search').val();
                let currentFreguesia = $('#freguesia_search').val();
                let currentStoreLocation = $('#store_location').val();
                let currentStoreAddress = $('#address').val();
                let that = this;
                
                // ADD STORE
                try {
                    
                    //const res = await axios.post('/api/storesNew', { name: currentStore, price: currentPrice, freguesia: currentFreguesia })

                    // NOVO FORMATO
                    const res = await axios.post('/api/storesNewLocation', { name: currentStore, price: currentPrice, freguesia: currentFreguesia, location: currentStoreLocation, address: currentStoreAddress })

                } catch (e) {

                    // that.newStoreError = true;
                    if (e.response) {
                        if (e.response.status == 451) {
                            utils.addVueFlash("danger", "#confirmStore .modal-body", 'Já existe uma loja com este nome.');
                        } 
                    }

                    return false; 
                }

            },
            addStore: function(data) {

                let currentStore = data.target ? $('#store').val() : data.store;
                let currentPrice = data.target ? $('#price').val() : data.price;
                currentPrice = currentPrice.replaceAll(',', '');
                let that = this;
                let storeTable = $('#store-table');
                let storeArrayField = $('#stores');
               
                if(isNaN(currentPrice)) {
                    utils.addFlash('danger', '.subpage', 'Preço inválido.');
                    return false;
                }
                
                // If a valid store is being added, or it comes from saved data
                if(that.validStore || (!data.target && !data.submitting)) {        
                    var recordExists = that.tableContains(storeTable, currentStore);
                    if(recordExists) {
                        //utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Loja já adicionada.');
                        // Validation Error
                    } else {

                        // Add valid store
                        storeTable.append("<tr data-store='" + currentStore + "'>\
                        <td style=\"width:1px;cursor:pointer;\"><i class='fa fa-times remove-icon'></i></td>\
                        <td>" + currentStore + "</td>\
                        <td>" + (currentPrice || "-") + "</td></tr>");      
                        
                        // Set remove icon callback
                        $('.remove-icon').off('click').on('click', function(e) {
                            var currentStore = $(e.currentTarget).parents('tr').data('store');
                            console.log($(e.currentTarget).parents('tr'));
                            that.removeStore(e.currentTarget, currentStore);
                        });

                        if(that.multiStores) {
                            // Display results table
                            storeTable.parents('.add-record-table').show();
                        }
                        
                        // Add valid store to hidden field
                        var storeList = storeArrayField.val() == "" ? 
                                        [] : 
                                        storeArrayField.val().split(";");
                        storeList.push(currentStore + "||" + currentPrice);
                        storeArrayField.val(storeList.join(";"));

                        // Erase search field
                        console.log(that.multiStores);
                        if(that.multiStores) {
                            // $('#store').val("");    
                            this.store = "";
                        }   

                    }

                    if(that.multiStores) {
                        that.validStore = false;
                    } else {
                        that.validStore = true;
                    }

                    return true;

                } else {

                    if(data.submitting) {
                        this.newStore = $('#store').val();
                        this.newPrice = $('#price').val();
                    }
 
                    $('#storeModal').modal('toggle');
                    if(that.newMap!=null) {
                        that.newMap.setTarget(null);
                        that.newMap = null;
                    }
                    that.newMap = window.olMap.display('osm_store_map');
                    var layer = window.olMap.addLayer('newStore', that.newMap);
                    that.newMap.addEventListener('click', (event) => ((featureCoordField) => {    
                        that.setCaopFromCoord(event, featureCoordField);
                    })($('#store_location')));
                    
                    // If browser supports location, add location button
                    if(("geolocation" in navigator)) {

                        // get position
                        navigator.geolocation.getCurrentPosition(pos => {
                            console.log(pos.coords);
                            
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
                    

                    return false;
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
            fetchBrand() {
                /* Autocomplete Brand */
                this.validBrand = false;
                let that = this;
                if(that.newProduct.brand.name != '')
                {
                    axios.get('/api/brands/fetch/' + this.newProduct.brand.name)
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
                            that.newProduct.brand.name = brand;  
                            that.validBrand = true;
                            $('#brandList').fadeOut();  
                        });
                    })
                    .catch(error => {
                        console.log(error);
                    });
                    
                } else {
                    this.validBrand = true;
                }

            },
            removeAfter(selector, currentPos, fieldName) {
                $(selector).each(function(i, el) {
                    var curEl = $(el);
                    if(parseInt(curEl.attr('pos')) > currentPos ) {
                        curEl.remove();
                    }
                });
                $(selector).last().attr('name', fieldName);
            },
            updateCategories(categories) {
                let category1 = $('.category_select');
                category1.val(categories[0]);
                category1.trigger('change');
                console.log($('.category_select').length);
      
            },
            updateStores(stores) {
                let storeList = stores.split(';');
                for(var i = 0; i < storeList.length; i++) {
                    let storeDetails = storeList[i].split("||");
                    this.addStore({ store: storeDetails[0], price: storeDetails[1] });
                }
            },
            getBase64Image(img) {
                var canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;

                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0);

                var dataURL = canvas.toDataURL("image/png");

                return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
            },
            updateImage(dataImage) {
                
                document.getElementById('output_image').src = window.URL.createObjectURL(dataImage);
                this.file = dataImage;
                $("#image").trigger("change");

            },
            updateSavedProduct() {
                // Update form with saved data
                $('#overlay').fadeIn();
                if(this.newProduct.categoryCascade.length) {
                    this.updateCategories(this.newProduct.categoryCascade);
                }
                if(this.newProduct.stores) {
                    this.updateStores(this.newProduct.stores);
                }
                console.log(this.newProduct.savedImage);
                if(this.newProduct.savedImage) {
                    this.updateImage(this.newProduct.savedImage);
                }
                if(this.newProduct.rating) {
                    for(var r = 0; r < this.newProduct.rating; r++) {
                        $($('.glyphicon-star')[r]).addClass('selected');
                        $($('.glyphicon-star')[r]).addClass('full');
                    }
                }
                $('#overlay').fadeOut();
            }
        }, 
        mounted() {
            this.updateSavedProduct();
        },
        created() {

            init.methods.initDoc();

            let that = this;
            // this.$store.dispatch('resetProduct');
            this.$store.dispatch('fetchTags');
            this.$store.dispatch('fetchDistritos');

            // Moved to AppComponent
            // $('body').click(function(evt){    
    
            //     if($(evt.target).hasClass('suggestion')){
            //         return;
            //     }        
        
            //     $('#brandList').fadeOut(); 
            //     $('#storeList').fadeOut();  
        
            // });

            // Categories
            $(document).on('change', '.category_select', function(e) {
                var currentSelect = $(e.currentTarget);
                var currentLevel = currentSelect.attr('pos');
                console.log("CURRENT LEVEL " + currentLevel);
                var category_id = currentSelect.val();
                if(category_id != '')
                {
                    if($('#overlay:hidden').length) $('#overlay').fadeIn();
                    
                    axios.get('/api/categories/fetch/' + category_id)
                    .then(res => {
            
                        // Remove all select boxes after the current one
                        that.removeAfter('.category_select', currentLevel, 'category');
        
                        let subCategoryList = res.data.data;
    
                        // If there are sub categories to add
                        if(subCategoryList.length) {
                            // Replace previous category to be saved
                            $('.category_select').removeAttr('name');
                            let newBox = '<select name="category" class="form-control input-lg category_select" required="required" pos="2">\
                            <option value="">Selecione</option></select';
                            // Append new category box to form
                            $('.category_select').last().after(newBox);     

                            // Append new categories to box
                            for(var i=0; i < subCategoryList.length; i++) {
                                console.log("ADDING");
                                $('.category_select').last().append($('<option>', { value : subCategoryList[i].id })
                                .text(subCategoryList[i].name));
                            }

                            let addedBox = $('.category_select').last();

                            // Set new category select position 
                            addedBox.attr('pos', parseInt(currentSelect.attr('pos')) + 1);

                            // Select value if previously saved
                            if(that.newProduct.categoryCascade.length > currentSelect.attr('pos')) {
                                addedBox.last().val(that.newProduct.categoryCascade[currentSelect.attr('pos')]);
                                addedBox.trigger('change');
                            } else {
                                $('#overlay').fadeOut();
                            }

                        
                        } else {
                            $('#overlay').fadeOut();
                        }



                    })
                    .catch(error => {
                        //utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Erro ao obter informações de categorias.');
                        // ERROR
                        // Unload spinner
                        console.log(error);
                        $('#overlay').fadeOut(); 
                    });
                    
                } else {
                    // Remove all select boxes after the current one
                    that.removeAfter('.category_select', currentLevel, 'category');
                    $('#overlay').fadeOut(); 
                }
            });

            // Images

            // Load first image on screen
            $(document).on("change", "#image", function(e) {
                // Finish loading first image
                $("#output_image").off('load').on("load", function() {
                    $("#image").hide();
                    $("label[for='image'] .label-text").first().text('Mudar Imagem');
                    $('.custom-image-upload.extra').show();
                });
                if(typeof e.currentTarget.files[0] != "undefined")
                    document.getElementById('output_image').src = window.URL.createObjectURL(e.currentTarget.files[0]);
            });

            $(document).off("change", ".extra-image").on("change", ".extra-image", function() {
                console.log("Changing");
                var currentLastImage = $('.extra-image').last().attr('pos');
                var nextImage = (parseInt(currentLastImage) + 1);


                // Add new image to grid
                $('.image-display-area div.row').append('<div class="col-md-3 col-xs-6 col-sm-6 extra-image-container">\
                            <img src="" name="extra_photos[]" pos="' + currentLastImage +'"class="output-image extra">\
                            <a href="#!" class="delete-image-link" pos="' + currentLastImage + '"><i class="fa fa-trash"></i></a>\
                        </div>');
                $(".output-image.extra[pos='" + currentLastImage + "']").attr('src', window.URL.createObjectURL(this.files[0]));
                
                // Add new image chose button
                // $('.extra-image').last().after('<input id="extra_{{ $image_name }}_' + nextImage + '" name="{{ $image_name }}_' + nextImage + '" pos="' + nextImage + '" class="extra-image" type="file" />');
                $('.extra-image').last().after('<input id="extra_image_' + nextImage + '" name="extra_photos[]" pos="' + nextImage + '" class="extra-image" type="file" />');
                // Update label click image
                $("label[for='extra_image_" + currentLastImage + "']").attr('for', 'extra_image_' + nextImage);
            });
            
            // Toggle delete link
            $(document).on("mouseenter", ".extra-image-container", function(e) {
                var imageContainer = $(e.currentTarget);
                imageContainer.find('.delete-image-link').show();
                imageContainer.find('img').css('opacity', '0.3');
            });
            $(document).on("mouseleave", ".extra-image-container", function(e) {
                var imageContainer = $(e.currentTarget);
                imageContainer.find('.delete-image-link').hide();
                imageContainer.find('img').css('opacity', '1');
            });

            // Delete image
            $(document).on('click', '.delete-image-link', function(e) {
                
                var container = $(e.currentTarget).parent();
                var imageToDelete = container.find('img').data('id');
                var currentImagesToDelete = $('#deleted_images').val();
                var pos = $(e.currentTarget).attr('pos');
                
                // List files to remove on edition
                $('#deleted_images').val(currentImagesToDelete + (currentImagesToDelete != "" ? ";" : "") + imageToDelete);
                // Remove from form
                container.remove();
                document.getElementById('extra_image_' + pos).value = null;

            });

            


        }
    }
   
</script>