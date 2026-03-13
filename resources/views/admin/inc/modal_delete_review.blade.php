<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Eliminar Avaliação</h4>
</div>
{!! Form::open(['action' => 'ReviewsController@delete', 'method' => 'POST','id' => 'review-form']) !!}
    
    {{ csrf_field() }}
    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
    <div class="modal-body">      

        Confirma a eliminação da avaliação? Isto vai fazer com que as estatísticas associadas à avaliação também sejam eliminados.

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{-- {{Form::hidden('_method', 'DELETE')}} --}}
        {{Form::hidden('id', $data["review"]->id)}}
        
        {{Form::submit('Confirmar', ['class' => 'btn btn-lg btn-primary', 'id' => 'delete-review'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>
