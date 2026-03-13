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

    $(document).on('click', '#submit-review', function(){
        $('#review-form').submit();
    }); 

    // On clicking edit chain button
    $('.editReview').on('click', function(){

        // Prepare chain data
        var this_id = $(this).attr('data-id');
        var this_action = $(this).attr('data-action');
        var page = window.location.href.split("?")[0].replace("#", "");

        // Clear modal
        $('#editRecordModal .load_modal').html('');
        $('#newRecordModal .load_modal').html('');

        // For the edit button
        if(this_action == 'edit'){

            // Get the modal screen to load
            $.get(page + "/loadModal" + "/" + this_id, function( data ) {

                $('#editRecordModal').modal();
                $('#editRecordModal').on('shown.bs.modal', function(){
                    
                    // Load modal screen
                    $('#editRecordModal .load_modal').html(data);
                    // chainManager.init();   
                    // chainManager.modalId = 'editRecordModal';   

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
                    // productManager.init();   
                    // productManager.modalId = 'deleteRecordModal';   
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