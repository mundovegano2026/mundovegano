import utils from '../utils.js';

var chainManager = {

    isValid: false,
    chainForm: "",
    chainErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        chainManager.chainForm = $('#chain-form');
        chainManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        chainManager.chainForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        chainManager.isValid = true;
        chainManager.chainErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        chainManager.chainForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                chainManager.isValid = false;
                chainManager.chainErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields
        if($('#name').val()!="") {

            if($('#name').val()!=chainManager.originalName) {              
                // Unique name
                $.ajax({
                    url: window.env_basepath + "admin/chains/validateName",
                    method:"POST",
                    data:{name: $('#name').val(), _token:_token},
                    success:function(data){

                        // If name and brand unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            chainManager.isValid = false;
                            chainManager.chainErrorMessageList.push("Nome de cadeia já existe.");
                            chainManager.showErrors();
                        } else if(chainManager.isValid) {
                            chainManager.validateLogic();
                        } else {
                            chainManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + chainManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + chainManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                chainManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            chainManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(chainManager.isValid) {
            chainManager.chainForm.submit();
        } else {
            chainManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + chainManager.modalId + ' .modal-body');
        if(!chainManager.isValid) {
            for(var i=chainManager.chainErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + chainManager.modalId + ' .modal-body', chainManager.chainErrorMessageList[i]);
            }
        }
    }


}

window.chainManager = chainManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-chain', function(){
        chainManager.validate();
    }); 

    // On clicking edit chain button
    $('.editChain').on('click', function(){

        // Prepare chain data
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
                    chainManager.init();   
                    chainManager.modalId = 'newRecordModal';     
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
                    chainManager.init();   
                    chainManager.modalId = 'editRecordModal';   

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
                    chainManager.init();   
                    chainManager.modalId = 'deleteRecordModal';   
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