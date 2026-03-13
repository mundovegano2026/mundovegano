<input id="{{ $name }}" name="{{ $name }}" value="{{ isset($value) ? str_replace(";", ",", $value) : '' }}" placeholder="Adicione as tags relevantes">
<script>
    $('#{{ $name }}').tagator({
        autocomplete: @json($tags)
    });
</script>