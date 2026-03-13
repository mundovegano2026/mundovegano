<?php 
$mainImage = $images == null ? null : $images->where('type_id', 1)->last(); 
$extraImages = $images == null ? array() : $images->where('type_id', 2); 
$extraCount = 2; // First extra image position
?>
<div class="image-upload-widget">
    <input type="hidden" id="deleted_images" name="deleted_images" />
    <img id="output_{{ $image_name }}" src="{{ isset($mainImage->path) ? '/storage/' . (isset($entity) ? $entity : 'product') . '_images/' . $mainImage->path : '' }}" class="output-image">
    <div class="image-upload-area">
        <!-- First Image -->
        <label for="{{ $image_name }}" class="custom-image-upload">
            <i class="fa fa-cloud-upload"></i> <span class='label-text'>Carregar Imagem</span>
        </label>
        <input id="{{ $image_name }}" name="{{ $image_name }}" type="file" />

        <!-- Extra Images -->
        @if(!isset($no_extra))        
            <label for="extra_{{ $image_name }}_1" style="display: none" class="custom-image-upload extra">
                <i class="fa fa-cloud-upload"></i> <span class='label-text'>Carregar Adicional</span>
            </label>
            <input id="extra_{{ $image_name }}_1" name="extra_photos[]" pos="1" class="extra-image" type="file" />
        @endif
    </div>
    <div class="image-display-area">
        <div class="row">
            @foreach($extraImages as $extraImage)
            <div class="col-md-3 extra-image-container">
                <img src="{{ isset($extraImage->path) ? '/storage/' . (isset($entity) ? $entity : 'product') . '_images/' . $extraImage->path : '' }}" data-id="{{ isset($extraImage->id) ? $extraImage->id : 0 }}" name="extra_photos[]" pos="{{ $extraCount++ }}" class="output-image extra">
                <a href="#" class="delete-image-link"><i class="fa fa-trash"></i></a>
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>

    var defer = function(method) {
        if (window.jQuery) {
            method();
        } else {
            setTimeout(function() { defer(method) }, 50);
        }
    }

    var init = function() {
        // Finish loading first image
        $("#output_{{ $image_name }}").on("load", function() {
            $("#{{ $image_name }}").hide();
            $("label[for='{{ $image_name }}'] .label-text").first().text('Mudar Imagem');
            $('.custom-image-upload.extra').show();
        });

        // Load first image on screen
        $("#{{ $image_name }}").on("change", function() {
            document.getElementById('output_{{ $image_name }}').src = window.URL.createObjectURL(this.files[0]);
        });

        // Load extra image on screen
        $(document).off('change', ".extra-image");
        $(document).on("change", ".extra-image", function() {
            console.log("Changing");
            var currentLastImage = $('.extra-image').last().attr('pos');
            var nextImage = (parseInt(currentLastImage) + 1);

            // Add new image to grid
            $('.image-display-area div.row').append('<div class="col-md-3 extra-image-container">\
                        <img src="" name="extra_photos[]" pos="' + currentLastImage +'"class="output-image extra">\
                        <a href="#" class="delete-image-link"><i class="fa fa-trash"></i></a>\
                    </div>');
            $(".output-image.extra[pos='" + currentLastImage + "']").attr('src', window.URL.createObjectURL(this.files[0]));
            
            // Add new image chose button
            // $('.extra-image').last().after('<input id="extra_{{ $image_name }}_' + nextImage + '" name="{{ $image_name }}_' + nextImage + '" pos="' + nextImage + '" class="extra-image" type="file" />');
            $('.extra-image').last().after('<input id="extra_{{ $image_name }}_' + nextImage + '" name="extra_photos[]" pos="' + nextImage + '" class="extra-image" type="file" />');
            // Update label click image
            $("label[for='extra_{{ $image_name }}_" + currentLastImage + "']").attr('for', 'extra_{{ $image_name }}_' + nextImage);
        });

        // Toggle delete link
        $(document).on("mouseenter", ".extra-image-container", function() {
            var imageContainer = $(this);
            imageContainer.find('.delete-image-link').show();
            imageContainer.find('img').css('opacity', '0.3');
        });
        $(document).on("mouseleave", ".extra-image-container", function() {
            var imageContainer = $(this);
            imageContainer.find('.delete-image-link').hide();
            imageContainer.find('img').css('opacity', '1');
        });

        // Delete image
        $(document).on('click', '.delete-image-link', function() {
            
            var container = $(this).parent();
            var imageToDelete = container.find('img').data('id');
            var currentImagesToDelete = $('#deleted_images').val();
            
            // List files to remove on edition
            $('#deleted_images').val(currentImagesToDelete + (currentImagesToDelete != "" ? ";" : "") + imageToDelete);
            // Remove from form
            container.remove();

        });
    }
    defer(init);
</script>