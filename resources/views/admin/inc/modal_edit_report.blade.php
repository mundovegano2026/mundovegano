<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Resolver Denúncia</h4>
</div>
{!! Form::open(['action' => ['Product_reportsController@update', $data["product_report"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'report-form']) !!}
{{-- {!! Form::open(['action' => route('price_update'), 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'price-form']) !!} --}}
{{ csrf_field() }}
    <div class="modal-body">      
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Estado</legend>       
                    <div class="form-group">    
                        <select name="status" class="form-control status_select input-lg" required="required" data-desc="estado">
                            <option value="">Selecione</option>
                            @foreach($data["statuses"] as $status)
                                <option <?php if($status->id == $data["product_report"]->status_id) { echo "selected"; }?> value="{{$status->id}}">{{ $status->type }}</option>
                            @endforeach
                        </select>                                
                    </div>      
                </fieldset>  
                <fieldset>
                    <legend>Detalhes</legend> 
                    {{Form::hidden('id', $data["product_report"]->id, ['readonly' => 'readonly', 'id' => 'id'])}}
                    <div class="form-group">
                        <?php echo Form::label('type', 'Tipo'); ?>
                        {{Form::text('type', $data["product_report"]->type->type, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Tipo', 'id' => 'type', 'data-desc' => 'type'])}}
                    </div>
                    <div class="form-group">
                        <?php echo Form::label('product_name', 'Produto'); ?><a href="products?prod_id={{ $data["product_report"]->product->id }}" title="Ver produto" target="_blank"><i class="fa fa-search" style="cursor: pointer; margin-left: 5px"></i></a>
                        {{Form::text('product_name', $data["product_report"]->product->name, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Nome do Produto'])}}
                    </div> 
                    <div class="form-group">
                        <?php echo Form::label('obs', 'Observações'); ?>
                        {{Form::text('obs', $data["product_report"]->obs, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => '-', 'id' => 'obs'])}}
                    </div>
                </fieldset> 
                <fieldset>
                    <legend>Utilizador</legend> 
                    @if($data["product_report"]->user != null)
                    <div class="form-group">
                        <?php echo Form::label('user_name', 'Nome'); ?>
                        {{Form::text('product_name', $data["product_report"]->user->name, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Nome do Utilizador'])}}
                    </div> 
                    <div class="form-group">
                        <?php echo Form::label('email', 'Email'); ?>
                        {{Form::text('email', $data["product_report"]->user->email, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => '-', 'id' => 'email'])}}
                    </div>
                    @else
                        <p>Utilizador da denúncia Anónimo.</p>
                    @endif
                </fieldset>  
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'POST')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-primary', 'id' => 'submit-report'])}}
    </div>
{!! Form::close() !!}