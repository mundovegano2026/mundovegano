import utils from '../utils.js';

var productManager = {

    mode: "", // create / edit
    isValid: false,
    modalId: "",
    originalName: "",
    originalBrand: "",
    productErrorMessageList: [],
    validStore: false,
    validChain: false,
    validBrand: true,
    productForm: "",
    storeTable: "",
    chainTable: "",
    storeSearchField: "",
    chainSearchField: "",
    priceField: "",
    chainPriceField: "",
    storeArrayField: "",
    imageSizeLimit: 2, // 2MB,
    init: function() {
        productManager.productForm = $('#product-form');
        productManager.storeTable = $('#wid-id-store table tbody');
        productManager.chainTable = $('#wid-id-chain table tbody');
        productManager.storeSearchField = $('#store');
        productManager.chainSearchField = $('#chain');
        productManager.storeArrayField = $('#stores');
        productManager.chainArrayField = $('#chains');
        productManager.priceField = $('#price');
        productManager.chainPriceField = $('#chain-price');
        productManager.mainImage = $('#output_main_image');
        productManager.mainImageField = $('#main_image');
        productManager.brandField = $('#brand');
        productManager.originalName = $('#name').val();
        productManager.originalBrand = $('#brand').val();

        $('#add-store').off('click').on('click', function() {
            var currentStore = productManager.storeSearchField.val(); 
            var currentPrice = productManager.priceField.val();    
            productManager.addStore(currentStore, currentPrice);
        });

        $('#add-chain').off('click').on('click', function() {
            var currentChain = productManager.chainSearchField.val(); 
            var currentChainPrice = productManager.chainPriceField.val();    
            productManager.addChain(currentChain, currentChainPrice);
        });

        // $(document).on('click', '.store-form .remove-icon', function(el) {
        //     var currentStore = $(this).parents('tr').data('store');
        //     productManager.removeStore(this, currentStore);
        // });

        // $(document).on('click', '.chain-form .remove-icon', function(el) {
        //     var currentChain = $(this).parents('tr').data('chain');
        //     productManager.removeChain(this, currentChain);
        // });
        $(document).delegate( '.store-form .remove-icon', 'click', function(el) {
            var currentStore = $(this).parents('tr').data('store');
            productManager.removeStore(this, currentStore);
        });

        $(document).delegate( '#product-form .remove-icon', 'click', function(el) {
            var currentStore = $(this).parents('tr').data('store');
            productManager.removeStore(this, currentStore);
        });

        $(document).delegate('.chain-form .remove-icon', 'click', function(el) {
            var currentChain = $(this).parents('tr').data('chain');
            productManager.removeChain(this, currentChain);
        });
        if(productManager.storeArrayField.val() != "") productManager.storeTable.parents('.add-record-table').show();
        if(productManager.chainArrayField.val() != "") productManager.chainTable.parents('.add-record-table').show();
    },
    activation: function(type, id, token) {

        let url = '/admin/products/' + type;
        
        $.post(url, {id: id, "_token": token}, function(result){
            // window.location.reload();
            if(result.error) {
                utils.addFlash('danger', '#content', result.message);
            } else {
                window.location.reload();
            }
        });
    },
    addStore: function(currentStore, currentPrice) {

        if(isNaN(currentPrice)) {
            utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Preço inválido.');
            return;
        }

        if(productManager.validStore) {        
            var recordExists = utils.tableContains(productManager.storeTable, currentStore);
            if(recordExists) {
                utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Loja já adicionada.');
            } else {

                // Display results table
                productManager.storeTable.parents('.add-record-table').show();

                // Add valid store
                productManager.storeTable.append("<tr data-store='" + currentStore + "'>\
                <td style=\"width:1px;cursor:pointer;\"><i class='fa fa-times remove-icon'></i></td>\
                <td>" + currentStore + "</td>\
                <td>" + "-" + "</td>\
                <td>" + (currentPrice || "-") + "</td></tr>");

                // Add valid store to hidden field
                var storeList = productManager.storeArrayField.val() == "" ? 
                                [] : 
                                productManager.storeArrayField.val().split(";");
                storeList.push(currentStore + "||" + currentPrice);
                productManager.storeArrayField.val(storeList.join(";"));

                // Erase search field
                productManager.storeSearchField.val("");
            }
            productManager.validStore = false;
        } else {
            utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Loja inválida.');
        }


    },
    addChain: function(currentChain, currentChainPrice) {

        if(isNaN(currentChainPrice)) {
            utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Preço inválido.');
            return;
        }

        if(productManager.validChain) {        
            var recordExists = utils.tableContains(productManager.chainTable, currentChain);
            if(recordExists) {
                utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Cadeia já adicionada.');
            } else {

                // Display results table
                productManager.chainTable.parents('.add-record-table').show();

                // Add valid store
                let newIcon = productManager.chainTable.append("<tr data-chain='" + currentChain + "'>\
                <td style=\"width:1px;cursor:pointer;\"><i class='fa fa-times remove-icon'></i></td>\
                <td>" + currentChain + "</td>\
                <td>" + (currentChainPrice || "-") + "</td></tr>");

                // Add valid store to hidden field
                var chainList = productManager.chainArrayField.val() == "" ? 
                                [] : 
                                productManager.chainArrayField.val().split(";");
                chainList.push(currentChain + "||" + currentChainPrice);
                productManager.chainArrayField.val(chainList.join(";"));

                // Erase search field
                productManager.chainSearchField.val("");
            }
            productManager.validChain = false;
        } else {
            utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Cadeia inválida.');
        }


    }, 
    removeStore: function(el, currentStore) {
        // Remove store from table
        $(el).parents('tr').remove();
        // Remove store from hidden field
        var storeList = productManager.storeArrayField.val().split(";");

        storeList = storeList.filter(function(value, index, arr){ return value != currentStore;});

        productManager.storeArrayField.val(storeList.join(";"));
        if(!productManager.storeArrayField.val()) {
                // Hide results table
                productManager.storeTable.parents('.add-record-table').hide();
        }
    },
    removeChain: function(el, currentChain) {
        // Remove store from table
        $(el).parents('tr').remove();
        // Remove store from hidden field
        var chainList = productManager.chainArrayField.val().split(";");

        chainList = chainList.filter(function(value, index, arr){ return value != currentChain;});

        productManager.chainArrayField.val(chainList.join(";"));
        if(!productManager.chainArrayField.val()) {
                // Hide results table
                productManager.chainTable.parents('.add-record-table').hide();
        }
    },
    validate: function() {
        
        // Clear error styles
        productManager.productForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        productManager.isValid = true;
        productManager.productErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        productManager.productForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                productManager.isValid = false;
                productManager.productErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });

        // Brand
        if(!productManager.validBrand) {
            productManager.brandField.addClass('is-invalid');  
            productManager.isValid = false;
            productManager.productErrorMessageList.push("Marca inválida.");
        } 

        // Validate Unique Fields
        if($('#name').val()!="" && productManager.validBrand) {

            if($('#name').val()!=productManager.originalName || $('#brand').val()!=productManager.originalBrand) {              
                // Unique name + brand validation
                $.ajax({
                    url: window.env_basepath + "admin/products/validateName",
                    method:"POST",
                    data:{name: $('#name').val(),brand: $('#brand').val(), _token:_token},
                    success:function(data){

                        // If name and brand unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            productManager.isValid = false;
                            productManager.productErrorMessageList.push("Nome de produto já existe.");
                            productManager.showErrors();
                        } else if(productManager.isValid) {
                            productManager.validateLogic();
                        } else {
                            productManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + productManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                productManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            productManager.showErrors();
        }

    },
    validateLogic: function() {

        // Tags
        var tags = "";
        $('#tagator_tag-input .tagator_tags .tagator_tag').each(function(i, el) {
            tags += $(el).text().slice(0,-1) + ";";
        });
        $('#tags').val(tags);

        var imageSizeLimitBytes = productManager.imageSizeLimit * 1024 * 1024;

        // Capacity
        if($('#capacity').val() != '' && $('#capacity_unit').val() == '') {
            productManager.isValid = false;
            productManager.productErrorMessageList.push("Deve selecionar unidade para capacidade do artigo.");
        }

        // Mandatory Image
        if(!productManager.mainImage.attr("src")) {
            productManager.isValid = false;
            productManager.productErrorMessageList.push("Deve selecionar imagem principal do produto.");
        } else {
            // Image size limit
            if(productManager.mode == "create" && 
                (typeof productManager.mainImageField[0].files[0] != "undefined" && productManager.mainImageField[0].files[0].size > imageSizeLimitBytes)) {
                productManager.isValid = false;
                productManager.productErrorMessageList.push("Ficheiro de imagem demasiado grande (Limite " + productManager.imageSizeLimit + "MB).");
            }
        }
        
        // Extra images size limit
        $('.extra-image').each(function(i, el) {
            if(typeof $(el)[0].files != 'undefined' && 
                typeof $(el)[0].files[0] != 'undefined' &&
                $(el)[0].files[0].size > imageSizeLimitBytes) {
                productManager.isValid = false;
                productManager.productErrorMessageList.push("Ficheiro de imagem adicional demasiado grande (Limite " + productManager.imageSizeLimit + "MB).");
            }  
        });
                
        // Set error if form is invalid
        // utils.removeFlash('#' + productManager.modalId + ' .modal-body');
        // if(!productManager.isValid) {
        //     for(var i=productManager.productErrorMessageList.length-1; i>=0; i--) {
        //         utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', productManager.productErrorMessageList[i]);
        //     }
        // }
        //
        // If valid product, submit form
        // if(productManager.isValid) {
        //     productManager.productForm.submit();
        // }

        // Submit form if valid
        if(productManager.isValid) {
            productManager.productForm.submit();
        } else {
            productManager.showErrors();
        }


    },
    showErrors: function() {
        utils.removeFlash('#' + productManager.modalId + ' .modal-body');
        if(!productManager.isValid) {
            for(var i=productManager.productErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', productManager.productErrorMessageList[i]);
            }
        }
    }
}

window.productManager = productManager; 

$('document').ready(function() {

    // Review actions
    $(document).on('click', '.review-action', function() {
        
        var button = $(this);
        var endPoint = button.data('type') == 'up' ? 'upvote' : 'downvote';
        var review_id = button.data('review_id');
        var _token = $('input[name="_token"]').val();
        $('#overlay').fadeIn();

        $.ajax({
            url: window.env_basepath + "admin/reviews/" + endPoint,
            method:"POST",
            data:{ review_id:review_id, _token:_token },
            success:function(data){
                if(data) {
                    button.toggleClass('selected');
                    var oppositeButton = button.siblings('.review-action');
                    if(oppositeButton.hasClass('selected') && button.hasClass('selected')){
                        oppositeButton.toggleClass('selected');
                    }
                }
                $('#overlay').fadeOut();
            },
            error: function(xhr, error) {   
                
                utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Erro ao avaliar ação do utilizador.');

                // Unload spinner
                $('#overlay').fadeOut(); 

            }
        });

    });

    // On clicking edit product button
    $(document).on('click', '#submit-product', function(){
        productManager.validate();
    });
    
    // On clicking edit product button
    $('.editProduct').on('click', function(){

        // Prepare product data
        var this_id = $(this).attr('data-id');
        var this_action = $(this).attr('data-action');
        var page = window.location.href.split("?")[0].replace("#", "");

        // Clear modals
        $('#newRecordModal .load_modal').html('');
        $('#editRecordModal .load_modal').html('');
        $('#deleteRecordModal .load_modal').html('');

        // Set product manager mode
        productManager.mode = this_action;

        // For the create button
        if(this_action == 'create'){
            
            // Get the modal screen to load
            $.get(page + "/loadModal" + "/" + 0, function( data ) {

                $('#newRecordModal').modal();
                $('#newRecordModal').on('shown.bs.modal', function(){
                    
                    // Load modal screen
                    $('#newRecordModal .load_modal').html(data); 
                    productManager.init();   
                    productManager.modalId = 'newRecordModal';                

                });
                $('#newRecordModal').on('hidden.bs.modal', function(){
                    $('#newRecordModal .modal-body').data('');
                });
            });

        }

        // For the edit button
        if(this_action == 'edit'){

            // Get the modal screen to load
            $.get(page + "/loadModal" + "/" + this_id, function( data ) {

                $('#editRecordModal').modal();
                $('#editRecordModal').on('shown.bs.modal', function(){
                    
                    // Load modal screen
                    $('#editRecordModal .load_modal').html(data); 
                    productManager.init();   
                    productManager.modalId = 'editRecordModal';   
                    // Mark as validation screen
                    if(window.location.href.indexOf("validation") > -1) $('#validation').val("true");                 

                });
                $('#editRecordModal').on('hidden.bs.modal', function(){
                    $('#editRecordModal .modal-body').data('');
                });
            });
        }

        // For the edit button
        if(this_action == 'delete'){

            // Get the modal screen to load
            $.get(page + "/loadModalDelete" + "/" + this_id, function( data ) {

                $('#deleteRecordModal').modal();
                $('#deleteRecordModal').on('shown.bs.modal', function(){
                    
                    // Load modal screen
                    $('#deleteRecordModal .load_modal').html(data); 
                    productManager.init();   
                    productManager.modalId = 'deleteRecordModal';   
                    // Mark as validation screen
                    if(window.location.href.indexOf("validation") > -1) $('#validation').val("true");                 

                });
                $('#deleteRecordModal').on('hidden.bs.modal', function(){
                    $('#deleteRecordModal .modal-body').data('');
                });
            });
        }
    });

    $(document).on('change', '.category_select', function() {
        
        var currentSelect = $(this);
        var currentLevel = currentSelect.attr('pos');
        var category_id = currentSelect.val();
        if(category_id != '')
        {
            $('#overlay').fadeIn();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: window.env_basepath + "admin/categories/fetch",
                method:"POST",
                data:{id:category_id, _token:_token},
                success:function(data){
                    
                    // Remove all select boxes after the current one
                    utils.removeAfter('.category_select', currentLevel, 'category');
                    
                    // If there are sub categories to add
                    if(data != "") {
                        // Replace previous category to be saved
                        $('.category_select').removeAttr('name');
                        // Append new category box to form
                        $('.category_select').last().after(data);                         
                    }                   
                    // Set new category select position 
                    $('.category_select').last().attr('pos', parseInt(currentSelect.attr('pos')) + 1);
                    $('#overlay').fadeOut();
                },
                error: function(xhr, error) {   
                    
                    utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Erro ao obter informações de categorias.');
   
                    // Unload spinner
                    $('#overlay').fadeOut(); 
    
                }
            });
        } else {
            // Remove all select boxes after the current one
            utils.removeAfter('.category_select', currentLevel, 'category');
        }

    });

    /* Autocomplete Brand */
    $(document).on('input', '#brand', function(){ 
        productManager.validBrand = false;
        var query = $(this).val();
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: window.env_basepath + "admin/brands/fetch",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){
                    $('#brandList').fadeIn();  
                    $('#brandList').html(data);
                }
            });
        } else {
            productManager.validBrand = true;
        }
    });
    $(document).on('click', 'li.suggestion', function(){  
        $('#brand').val($(this).text());  
        productManager.validBrand = true;
        $('#brandList').fadeOut();  
    });      

    /* Autocomplete Store */
    $(document).on('input', '#store', function(){ 
        productManager.validStore = false;
        var query = $(this).val();
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: window.env_basepath + "admin/stores/fetch",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){
                    $('#storeList').fadeIn();  
                    $('#storeList').html(data);
                }
            });
        }
    });

    /* Autocomplete Chain */
    $(document).on('input', '#chain', function(){ 
        productManager.validChain = false;
        var query = $(this).val();
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: window.env_basepath + "admin/chains/fetchForProduct",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){
                    $('#chainList').fadeIn();  
                    $('#chainList').html(data);
                }
            });
        }
    });

    $(document).on('click', 'li.store-suggestion', function(){ 
        productManager.validStore = true; 
        $('#store').val($(this).find('.store-name').text());  
        $('#storeList').fadeOut();  
    }); 

    $(document).on('click', 'li.chain-suggestion', function(){ 
        productManager.validChain = true; 
        $('#chain').val($(this).find('.chain-name').text());  
        $('#chainList').fadeOut();  
    }); 

    
    // Remove Autocomplete lists
    $('body').click(function(evt){    
        
        if($(evt.target).hasClass('suggestion')){
            return;
        }        
 
        $('#brandList').fadeOut(); 
        $('#storeList').fadeOut();  
 
    });

});