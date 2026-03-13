<label for="capacity">Capacidade</label>
<div class="input-group">
    <input type="text" name="{{ $input['name'] }}" id="{{ $input['name'] }}" placeholder="{{ ucfirst($input['placeholder']) }}" data-desc="{{ $input['name'] }}" class="form-control {{isset($type) ? $type : ''}}" value="{{ isset($values) && $values["text"] > 0 ? $values["text"] : "" }}">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-display" tabindex="-1">{{ isset($values) && $values['select_text'] != '' ? $values["select_text"] : $select['placeholder'] }}</button>
        <input type="hidden" value="{{ isset($values) ? $values["select"] : '' }}" name="capacity_unit" id="capacity_unit">
        <button type="button" class="btn btn-default input-group-opener" tabindex="-1" onClick="$(this).parent().toggleClass('open')" aria-expanded="false">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="javascript:void(0);" onClick="window.cancelVal(this)">{{ $select['placeholder'] }}</a></li>
            @foreach($select['options'] as $option)
                <li><a href="javascript:void(0);" onClick="window.updateVal(this, '{{ $option[$select['option_value']] }}')">{{ $option[$select['option_value']] }}</a></li>
            @endforeach
            <li class="divider"></li>
            <li><a href="javascript:void(0);" onClick="window.cancelVal(this)">Cancelar</a></li>
        </ul>
    </div>
</div>

<script>

$("body").click(function(event) {
    if (!$(event.target).hasClass('input-group-opener')) {
    $('.input-group-btn').removeClass('open');
    }
});

window.cancelVal = function(el) {
    $(el).parents('.dropdown-menu').siblings('.btn-display').text('Selecionar');
    $('#capacity_unit').val('');
}

window.updateVal = function(el, value) {
    $(el).parents('.dropdown-menu').siblings('.btn-display').text(value);
    console.log($(el).parent());
    $('#capacity_unit').val(value);
}

$(document).on("input", ".decimal", function (e) {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});
</script>