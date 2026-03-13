<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Cadeia</h4>
</div>
{!! Form::open(['action' => ['ChainsController@update', $data["chain"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'chain-form']) !!}
{{ csrf_field() }}
    <div class="modal-body">      
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::text('name', $data["chain"]->name, ['class' => 'form-control', 'placeholder' => 'Nome', 'id' => 'name', 'required' => 'required', 'data-desc' => 'nome'])}}
                    </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-primary', 'id' => 'submit-chain'])}}
    </div>
{!! Form::close() !!}