
$('document').ready(function() {

    // $(document).on('click', '#submit-chain', function(){
    //     chainManager.validate();
    // }); 

    // On clicking edit chain button
    $('.deleteCat').on('click', function(){

        // Prepare chain data
        var this_id = $(this).attr('data-id');
        var this_name = $(this).attr('data-name');

        $('#del_id').val(this_id);
        $('#del_cat_name').text(this_name);

        $('#deleteRecordModal').modal('toggle');

    });
});