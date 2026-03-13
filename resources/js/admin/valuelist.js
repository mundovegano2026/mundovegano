import utils from '../utils.js';

var valuelistManager = {

    isValid: false,
    valuelistForm: "",
    valuelistErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        valuelistManager.valuelistForm = $('#valuelist-form');
        valuelistManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        valuelistManager.valuelistForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        valuelistManager.isValid = true;
        valuelistManager.valuelistErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        valuelistManager.valuelistForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                valuelistManager.isValid = false;
                valuelistManager.valuelistErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields
                console.log("Validating");        
        if($('#name').val()!="") {

            console.log($('#name').val()); 
            console.log(valuelistManager.originalName);        
            if($('#name').val()!=valuelistManager.originalName) {      
                // Unique name
                $.ajax({
                    url: window.env_basepath + "admin/valuelists/validateName",
                    method:"POST",
                    data:{name: $('#name').val(), _token:_token},
                    success:function(data){
           
                        // If name and valuelist unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            valuelistManager.isValid = false;
                            valuelistManager.valuelistErrorMessageList.push("Nome de valor já existe.");
                            valuelistManager.showErrors();
                        } else if(valuelistManager.isValid) {
                            valuelistManager.validateLogic();
                        } else {
                            valuelistManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + valuelistManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + valuelistManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                valuelistManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            valuelistManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(valuelistManager.isValid) {
            valuelistManager.valuelistForm.submit();
        } else {
            valuelistManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + valuelistManager.modalId + ' .modal-body');
        if(!valuelistManager.isValid) {
            for(var i=valuelistManager.valuelistErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + valuelistManager.modalId + ' .modal-body', valuelistManager.valuelistErrorMessageList[i]);
            }
        }
    }


}

window.valuelistManager = valuelistManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-valuelist', function(){
        valuelistManager.validate();
    }); 

    // On clicking edit valuelist button
    $('.editValuelist').on('click', function(){

        // Prepare valuelist data
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
                    valuelistManager.init();   
                    valuelistManager.modalId = 'newRecordModal';     
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
                    valuelistManager.init();   
                    valuelistManager.modalId = 'editRecordModal';   
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