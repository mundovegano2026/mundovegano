<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Nova Loja</h4>
</div>
{!! Form::open(['action' => ['StoresController@store', $data["store"]->id], 'method' => 'POST', 'id' => 'store-form', 'enctype' => 'multipart/form-data']) !!}
{{ csrf_field() }}
    <div class="modal-body">      
        <div class="row">                
            <fieldset>
                <legend>Dados da Loja</legend>
                <div class="form-group">
                    <?php echo Form::label('name', 'Nome'); ?>
                    {{
                        Form::text(
                            'name', 
                            '', 
                            ['class' => 'form-control', 'placeholder' => 'Nome', 'required' => 'required', 'data-desc' => 'nome'])
                    }}                            
                </div>
                <div class="form-group">
                    <?php echo Form::label('chain', 'Cadeia'); ?>
                    <input type="text" autocomplete="off" name="chain" id="chain" class="form-control input-lg" placeholder="Cadeia" />
                    <div id="chainList" class="suggestion-list"></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Localização</legend>
                <div class="form-group">
                    <?php echo Form::label('distrito', 'Distrito'); ?>
                    <select name="distrito" id="distrito" class="form-control" required="required" data-desc="distrito">
                        <option value="">Selecione</option>
                        @foreach($data["distritos"] as $key=>$value)
                            <option value="{{ $key}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <?php echo Form::label('concelho', 'Concelho'); ?>
                    <select name="concelho" id="concelho" class="form-control" required="required" data-desc="concelho">
                        <option value="">Selecione</option>
                    </select>

                </div>
                <div class="form-group">
                    <?php echo Form::label('freguesia', 'Freguesia'); ?>
                    <select name="freguesia" id="freguesia" class="form-control" required="required" data-desc="freguesia">
                        <option value="">Selecione</option>
                    </select>
                </div>
                <div class="form-group">
                    <?php echo Form::label('address', 'Morada'); ?>
                    <input type="text" autocomplete="off" name="address" id="address" class="form-control input-lg" placeholder="Morada" />
                </div>
                <div class="form-group form-center">
                    <p><small class="form-note">- Edite a localização da loja no mapa -</small></p>
                    {{Form::hidden('location', $data["store"]->text_location, ['class' => 'form-control', 'id' => 'location'])}}
                    <div class="map">
                        <div id="osm_map" style="height: 400px; width: 100%"></div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::button('Gravar', ['class' => 'btn btn-lg  btn-primary', 'id' => 'submit-store'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>

