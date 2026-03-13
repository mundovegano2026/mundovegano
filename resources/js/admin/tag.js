import utils from '../utils.js';

var tagManager = {

    isValid: false,
    tagForm: "",
    tagErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        tagManager.tagForm = $('#tag-form');
        tagManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        tagManager.tagForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        tagManager.isValid = true;
        tagManager.tagErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        tagManager.tagForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                tagManager.isValid = false;
                tagManager.tagErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields
                console.log("Validating");        
        if($('#name').val()!="") {

            console.log($('#name').val()); 
            console.log(tagManager.originalName);        
            if($('#name').val()!=tagManager.originalName) {      
                // Unique name
                $.ajax({
                    url: window.env_basepath + "admin/tags/validateName",
                    method:"POST",
                    data:{name: $('#name').val(), _token:_token},
                    success:function(data){
           
                        // If name and tag unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            tagManager.isValid = false;
                            tagManager.tagErrorMessageList.push("Nome de tag já existe.");
                            tagManager.showErrors();
                        } else if(tagManager.isValid) {
                            tagManager.validateLogic();
                        } else {
                            tagManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + tagManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + tagManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                tagManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            tagManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(tagManager.isValid) {
            tagManager.tagForm.submit();
        } else {
            tagManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + tagManager.modalId + ' .modal-body');
        if(!tagManager.isValid) {
            for(var i=tagManager.tagErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + tagManager.modalId + ' .modal-body', tagManager.tagErrorMessageList[i]);
            }
        }
    }


}

window.tagManager = tagManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-tag', function(){
        tagManager.validate();
    }); 

    // On clicking edit tag button
    $('.editTag').on('click', function(){

        // Prepare tag data
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
                    tagManager.init();   
                    tagManager.modalId = 'newRecordModal';     
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
                    tagManager.init();   
                    tagManager.modalId = 'editRecordModal';   
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