<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Nova Cadeia</h4>
</div>
{!! Form::open(['action' => ['ChainsController@store', $data["chain"]->id], 'method' => 'POST', 'id' => 'chain-form', 'enctype' => 'multipart/form-data']) !!}
{{ csrf_field() }}
    <div class="modal-body">      
        <div class="row">                
            <fieldset>
                <legend>Dados da Cadeia</legend>
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
        {{Form::button('Gravar', ['class' => 'btn btn-lg  btn-primary', 'id' => 'submit-chain'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>

