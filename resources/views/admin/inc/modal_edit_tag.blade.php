<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Tag</h4>
</div>
{!! Form::open(['action' => ['TagsController@update', $data["tag"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'tag-form']) !!}
{{ csrf_field() }}  
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
                <legend>Dados da Tag</legend>
                <div class="form-group">
                    <?php echo Form::label('name', 'Nome'); ?>
                    {{
                        Form::text(
                            'name', 
                            $data["tag"]->name, 
                            ['class' => 'form-control', 'placeholder' => 'Nome', 'required' => 'required', 'data-desc' => 'nome'])
                    }}                            
                </div>
            </fieldset>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-primary', 'id' => 'submit-tag'])}}
    </div>
{!! Form::close() !!}