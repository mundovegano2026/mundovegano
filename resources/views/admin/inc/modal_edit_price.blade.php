<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Preço</h4>
</div>
{!! Form::open(['action' => ['PricesController@update', $data["price"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'price-form']) !!}
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
                                <option <?php if($status->id == $data["price"]->status_id) { echo "selected"; }?> value="{{$status->id}}">{{ $status->type }}</option>
                            @endforeach
                        </select>                                
                    </div>      
                </fieldset>  
                <fieldset>
                    <legend>Detalhes</legend> 
                    {{Form::hidden('id', $data["price"]->id, ['readonly' => 'readonly', 'id' => 'id'])}}
                    <div class="form-group">
                        <?php echo Form::label('product_name', 'Produto'); ?><a href="products?prod_id={{ $data["price"]->product->id }}" title="Ver produto" target="_blank"><i class="fa fa-search" style="cursor: pointer; margin-left: 5px"></i></a>
                        {{Form::text('product_name', $data["price"]->product->name, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Nome do Produto', 'id' => 'product_name', 'data-desc' => 'nome'])}}
                    </div> 
                    <div class="form-group">
                        <?php echo Form::label('current_price', 'Preço Atual'); ?>
                        {{Form::text('current_price', isset($data["currentPrice"]) ? $data["currentPrice"]->price : "Sem informação de preço", ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Preço Atual', 'id' => 'current_price', 'data-desc' => 'current_price'])}}
                    </div>
                    <div class="form-group">
                        <?php echo Form::label('new_price', 'Novo Preço'); ?>
                        {{Form::text('new_price', $data["price"]->price, ['readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Novo Preço', 'id' => 'new_price', 'required' => 'required', 'data-desc' => 'new_price'])}}
                    </div>
                </fieldset>  
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'POST')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-primary', 'id' => 'submit-price'])}}
    </div>
{!! Form::close() !!}