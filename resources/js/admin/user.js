import utils from '../utils.js';

var userManager = {

    isValid: false,
    userForm: "",
    userErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        userManager.userForm = $('#user-form');
        userManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        userManager.userForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        userManager.isValid = true;
        userManager.userErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        userManager.userForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                userManager.isValid = false;
                userManager.userErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields
                console.log("Validating");        
        if($('#name').val()!="") {

            console.log($('#name').val()); 
            console.log(userManager.originalName);        
            if($('#name').val()!=userManager.originalName) {      
                // Unique name
                $.ajax({
                    url: window.env_basepath + "admin/users/validateName",
                    method:"POST",
                    data:{name: $('#name').val(), _token:_token},
                    success:function(data){
           
                        // If name and user unique key exists
                        if(data) {
                            $('#name').addClass('is-invalid');  
                            userManager.isValid = false;
                            userManager.userErrorMessageList.push("Nome de utilizador já existe.");
                            userManager.showErrors();
                        } else if(userManager.isValid) {
                            userManager.validateLogic();
                        } else {
                            userManager.showErrors();
                        }
                        
                    },
                    error: function(xhr, error) {   
                        utils.removeFlash('#' + userManager.modalId + ' .modal-body');
                        utils.addFlash('danger', '#' + userManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');

                    }
                });
            } else {
                userManager.validateLogic();
            }

        } else {
            // Set error if form is invalid
            userManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(userManager.isValid) {
            userManager.userForm.submit();
        } else {
            userManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + userManager.modalId + ' .modal-body');
        if(!userManager.isValid) {
            for(var i=userManager.userErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + userManager.modalId + ' .modal-body', userManager.userErrorMessageList[i]);
            }
        }
    }


}

window.userManager = userManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-user', function(){
        userManager.validate();
    }); 

    // On clicking edit user button
    $('.editUser').on('click', function(){

        // Prepare user data
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
                    userManager.init();   
                    userManager.modalId = 'newRecordModal';     
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
                    userManager.init();   
                    userManager.modalId = 'editRecordModal';   
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