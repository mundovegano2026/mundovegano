<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Nova Marca</h4>
</div>
{!! Form::open(['action' => ['BrandsController@store', $data["brand"]->id], 'method' => 'POST', 'id' => 'brand-form', 'enctype' => 'multipart/form-data', 'id' => 'brand-form']) !!}
    {{ csrf_field() }}
    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
    <div class="modal-body">      
        <div class="row">  
            <fieldset>
                <legend>Estado</legend>       
                <div class="form-group">    
                    <select name="status" class="form-control status_select input-lg" required="required" data-desc="estado">
                        <option value="">Selecione</option>
                        @foreach($data["statuses"] as $status)
                            <option <?php if($status->id == $data["status"]->id) { echo "selected"; }?> value="{{$status->id}}">{{ $status->type }}</option>
                        @endforeach
                    </select>                                
                </div>      
            </fieldset>              
            <fieldset>
                <legend>Dados da Marca</legend>
                <div class="form-group">
                    <?php echo Form::label('name', 'Nome'); ?>
                    {{
                        Form::text(
                            'name', 
                            '', 
                            ['class' => 'form-control', 'placeholder' => 'Nome', 'required' => 'required', 'data-desc' => 'nome'])
                    }}                            
                </div>
            </fieldset>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::button('Gravar', ['class' => 'btn btn-lg  btn-primary', 'id' => 'submit-brand'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>

