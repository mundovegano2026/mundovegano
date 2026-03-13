<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Valor</h4>
</div>
{!! Form::open(['action' => ['ValuelistsController@update', $data["valuelist"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'valuelist-form']) !!}
{{ csrf_field() }}  
    <div class="modal-body">      
        <div class="row">       
            <fieldset>
                <legend>Dados do Valor</legend>
                <div class="form-group">
                    <?php echo Form::label('name', 'Nome'); ?>
                    {{
                        Form::text(
                            'name', 
                            $data["valuelist"]->name, 
                            ['class' => 'form-control', 'placeholder' => 'Nome', 'required' => 'required', 'data-desc' => 'nome'])
                    }}                            
                </div>
                <div class="form-group">
                    <?php echo Form::label('value', 'Valor'); ?>  
                    {{
                        Form::textarea(
                            'value', 
                            $data["valuelist"]->value, 
                            ['class' => 'form-control', 'placeholder' => 'Valor textual'])
                    }}
                    <script>
                        $(document).ready(function() {

                            $('#value').trumbowyg();
                        });
                    </script>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-primary', 'id' => 'submit-valuelist'])}}
    </div>
{!! Form::close() !!}