<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Eliminar Loja</h4>
</div>
{!! Form::open(['action' => 'StoresController@delete', 'method' => 'POST','id' => 'store-form']) !!}
    
    {{ csrf_field() }}
    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
    <div class="modal-body">      

        Confirma a eliminação da loja {{ $data["store"]->name }} ? Isto vai fazer com que as estatísticas associadas à loja também sejam eliminadas.

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{-- {{Form::hidden('_method', 'DELETE')}} --}}
        {{Form::hidden('id', $data["store"]->id)}}
        
        {{Form::submit('Confirmar', ['class' => 'btn btn-lg btn-primary', 'id' => 'delete-store'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>
