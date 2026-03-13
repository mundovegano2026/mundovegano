<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Avaliação</h4>
</div>
{!! Form::open(['action' => ['ReviewsController@update', $data["review"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'review-form']) !!}
{{ csrf_field() }}
    <div class="modal-body">      
        <div class="row">      
            <div class="col-md-3 col-md-offset-9 push-bit" style="text-align: right">
                <i title="Marcar ação positiva" data-review_id="{{ $data["review"]->id}}" data-type="up" class="review-action fa fa-thumbs-up {{ $data["review"]->up_score ? 'selected' : '' }}"></i>
                <i title="Marcar ação negativa" data-review_id="{{ $data["review"]->id}}" data-type="down" class="review-action fa fa-thumbs-down {{ $data["review"]->down_score ? 'selected' : '' }}"></i>
            </div>
            <div class="col-md-12">
                <div class="form-group">    
                    <?php echo Form::label('status', 'Estado da Avaliação'); ?>  
                    <select name="status" class="form-control status_select input-lg" required="required" data-desc="estado">
                        <option value="">Selecione</option>
                        @foreach($data["statuses"] as $status)
                            <option <?php if($status->id == $data["status"]->id) { echo "selected"; }?> value="{{$status->id}}">{{ $status->type }}</option>
                        @endforeach
                    </select>                                
                </div> 
                <div class="form-group">
                    <?php echo Form::label('rating', 'Avaliação'); ?>  
                    @include('admin.inc.form-elements.rating', ['title' => '', 'score' => $data["review"]->score, 'no_score_message' => 'Sem avaliação'])
                </div>
                <div class="form-group">
                    <?php echo Form::label('comment', 'Comentário'); ?>  
                    {{
                        Form::textarea(
                            'comment', 
                            $data["review"]->comment, 
                            ['class' => 'form-control', 'placeholder' => '- Sem Comentário -', 'readonly' => 'readonly'])
                    }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-primary', 'id' => 'submit-review'])}}
    </div>
{!! Form::close() !!}