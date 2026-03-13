<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Eliminar Cadeia</h4>
</div>
{!! Form::open(['action' => 'ChainsController@delete', 'method' => 'POST','id' => 'chain-form']) !!}
    
    {{ csrf_field() }}
    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
    <div class="modal-body">      

        Confirma a eliminação da cadeia {{ $data["chain"]->name }} ? Isto vai fazer com que as estatísticas e as lojas associadas à cadeia também sejam eliminadas.

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{-- {{Form::hidden('_method', 'DELETE')}} --}}
        {{Form::hidden('id', $data["chain"]->id)}}
        
        {{Form::submit('Confirmar', ['class' => 'btn btn-lg btn-primary', 'id' => 'delete-chain'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>
