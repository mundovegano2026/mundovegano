import utils from '../utils.js';

var priceManager = {

    isValid: false,
    priceForm: "",
    priceErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        priceManager.priceForm = $('#price-form');
        priceManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        priceManager.priceForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        priceManager.isValid = true;
        priceManager.priceErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        priceManager.priceForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                priceManager.isValid = false;
                priceManager.priceErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields
        if($('#name').val()!="") {

            if($('#name').val()!=priceManager.originalName) {              
                // Unique name
                $.ajax({
                    url: window.env_basepath + "admin/prices/validateName",
                    method:"POST",
                    data:{name: $('#name').val(), _token:_token},
                    success:function(data){

                        // If name and brand unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            priceManager.isValid = false;
                            priceManager.priceErrorMessageList.push("Nome de cadeia já existe.");
                            priceManager.showErrors();
                        } else if(priceManager.isValid) {
                            priceManager.validateLogic();
                        } else {
                            priceManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + priceManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + priceManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                priceManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            priceManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(priceManager.isValid) {
            priceManager.priceForm.submit();
        } else {
            priceManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + priceManager.modalId + ' .modal-body');
        if(!priceManager.isValid) {
            for(var i=priceManager.priceErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + priceManager.modalId + ' .modal-body', priceManager.priceErrorMessageList[i]);
            }
        }
    }


}

window.priceManager = priceManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-price', function(){
        priceManager.validate();
    }); 

    // On clicking edit price button
    $('.editPrice').on('click', function(){

        // Prepare price data
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
                    priceManager.init();   
                    priceManager.modalId = 'newRecordModal';     
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
                    priceManager.init();   
                    priceManager.modalId = 'editRecordModal';   

                });
                $('#editRecordModal').on('hidden.bs.modal', function(){
                    $('#editRecordModal .modal-body').data('');
                });
            });
        }
    });
});