import utils from '../utils.js';

var reportManager = {

    isValid: false,
    reportForm: "",
    reportErrorMessageList: [],
    originalName: "",
    modalId: "",
    init: function() {
        reportManager.reportForm = $('#report-form');
        reportManager.originalName = $('#name').val();
    },
    validate: function() {
        
        // Clear error styles
        reportManager.reportForm.find('input, select').removeClass('is-invalid');

        // Init Validation
        reportManager.isValid = true;
        reportManager.reportErrorMessageList = [];
        var _token = $('input[name="_token"]').val();

        // Validate Required
        reportManager.reportForm.find('input[required], select[required]').each(function(i, el) {
            var field = $(el);
            if(field.val() == "") {            
                field.addClass('is-invalid');        
                reportManager.isValid = false;
                reportManager.reportErrorMessageList.push("Deve preencher " + field.data('desc') +  ".");
            }
        });
        
        // Validate Unique Fields
        if($('#name').val()!="") {
           
            reportManager.validateLogic();            

        } else {
            // Set error if form is invalid
            reportManager.showErrors();
        }

    },
    validateLogic: function() {
            
        // Submit form if valid
        if(reportManager.isValid) {
            reportManager.reportForm.submit();
        } else {
            reportManager.showErrors();
        }

    },
    showErrors: function() {
        utils.removeFlash('#' + reportManager.modalId + ' .modal-body');
        if(!reportManager.isValid) {
            for(var i=reportManager.reportErrorMessageList.length-1; i>=0; i--) {
                utils.addFlash('danger', '#' + reportManager.modalId + ' .modal-body', reportManager.reportErrorMessageList[i]);
            }
        }
    }


}

window.reportManager = reportManager; 


$('document').ready(function() {

    $(document).on('click', '#submit-report', function(){
        reportManager.validate();
    }); 

    // On clicking edit report button
    $('.editReport').on('click', function(){

        // Prepare report data
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
                    reportManager.init();   
                    reportManager.modalId = 'newRecordModal';     
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
                    reportManager.init();   
                    reportManager.modalId = 'editRecordModal';   

                });
                $('#editRecordModal').on('hidden.bs.modal', function(){
                    $('#editRecordModal .modal-body').data('');
                });
            });
        }
    });
});