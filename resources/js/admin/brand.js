import utils from '../utils.js';

var brandManager = {

    isValid: false,
    brandForm: "",
    brandErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        brandManager.brandForm = $('#brand-form');
        brandManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        brandManager.brandForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        brandManager.isValid = true;
        brandManager.brandErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        brandManager.brandForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                brandManager.isValid = false;
                brandManager.brandErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields    
        if($('#name').val()!="") {

            console.log($('#name').val()); 
            console.log(brandManager.originalName);        
            if($('#name').val()!=brandManager.originalName) {      
                // Unique name
                $.ajax({
                    url: window.env_basepath + "admin/brands/validateName",
                    method:"POST",
                    data:{name: $('#name').val(), _token:_token},
                    success:function(data){
 
                        console.log(data);          
                        // If name and brand unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            brandManager.isValid = false;
                            brandManager.brandErrorMessageList.push("Nome de marca já existe.");
                            brandManager.showErrors();
                        } else if(brandManager.isValid) {
                            brandManager.validateLogic();
                        } else {
                            brandManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + brandManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + brandManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                brandManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            brandManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(brandManager.isValid) {
            brandManager.brandForm.submit();
        } else {
            brandManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + brandManager.modalId + ' .modal-body');
        if(!brandManager.isValid) {
            for(var i=brandManager.brandErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + brandManager.modalId + ' .modal-body', brandManager.brandErrorMessageList[i]);
            }
        }
    }


}

window.brandManager = brandManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-brand', function(){
        brandManager.validate();
    }); 

    // On clicking edit brand button
    $('.editBrand').on('click', function(){

        // Prepare brand data
        var this_id = $(this).attr('data-id');
        var this_action = $(this).attr('data-action');
        var page = window.location.href.split("?")[0].replace("#", "");

        // Clear modal
        $('#editRecordModal .load_modal').html('');
        $('#newRecordModal .load_modal').html('');

        // For the create button
        if(this_action == 'create'){

            // Get the modal screen to load
            $.get(page + "/loadModal" + "/" + 0, function( data ) {

                $('#newRecordModal').modal();
                $('#newRecordModal').on('shown.bs.modal', function(){
                    
                    // Load modal screen
                    $('#newRecordModal .load_modal').html(data);
                    brandManager.init();   
                    brandManager.modalId = 'newRecordModal';     
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
                    brandManager.init();   
                    brandManager.modalId = 'editRecordModal';   
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
                    brandManager.init();   
                    brandManager.modalId = 'deleteRecordModal';   
                    // Mark as validation screen
                    if(window.location.href.indexOf("validation") > -1) $('#validation').val("true");                 

                });
                $('#deleteRecordModal').on('hidden.bs.modal', function(){
                    $('#deleteRecordModal .modal-body').data('');
                });
            });
        }
    });
});