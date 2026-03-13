<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Loja</h4>
</div>
{!! Form::open(['action' => ['StoresController@update', $data["store"]->id], 'method' => 'POST', 'id' => 'store-form', 'enctype' => 'multipart/form-data']) !!}
    {{ csrf_field() }}
    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
    <div class="modal-body">      
        <div class="row">  
            <fieldset>
                <legend>Estado</legend>       
                <div class="form-group">    
                    <select name="status" class="form-control status_select input-lg" required="required" data-desc="estado">
                        <option value="">Selecione</option>
                        @foreach($data["statuses"] as $status)
                            <option <?php if($status->id == $data["status"]->id) { echo "selected"; }?> value="{{$status->id}}">{{ $status->type }}</option>
                        @endforeach
                    </select>                                
                </div>      
            </fieldset>          
            <fieldset>
                <legend>Dados da Loja</legend>
                <div class="form-group">
                    <?php echo Form::label('name', 'Nome'); ?>
                    {{
                        Form::text(
                            'name', 
                            $data["store"]->name, 
                            ['class' => 'form-control', 'placeholder' => 'Nome'])
                    }}
                </div>
                    
                <div class="form-group">
                    <?php echo Form::label('chain', 'Cadeia'); ?>
                    <input type="text" autocomplete="off" name="chain" id="chain" class="form-control input-lg" placeholder="Cadeia" value="{{ isset($data["store"]->chain) ? $data["store"]->chain->name : '' }}" />
                    <div id="chainList" class="suggestion-list"></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Localização</legend>
                <div class="form-group">
                    <?php echo Form::label('distrito', 'Distrito'); ?>
                    <select name="distrito" id="distrito" class="form-control" required="required">
                        <option value="">Selecione</option>
                        @foreach($data["distritos"] as $key=>$value)
                            @if(isset($data["store"]->caop) && substr($data["store"]->caop->dicofre, 0, 2) == $key)                            
                                <option value="{{ $key}}" selected>{{ $value }}</option>
                            @else      
                            <option value="{{ $key}}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <?php echo Form::label('concelho', 'Concelho'); ?>
                    <select name="concelho" id="concelho" class="form-control" required="required">
                        <option value="">Selecione</option>
                        @foreach($data["concelhos"] as $key=>$value)
                            @if(substr($data["store"]->caop->dicofre, 0, 4) == $key)                            
                                <option value="{{ $key}}" selected>{{ $value }}</option>
                            @else      
                            <option value="{{ $key}}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <?php echo Form::label('freguesia', 'Freguesia'); ?>
                    <select name="freguesia" id="freguesia" class="form-control" required="required">
                        <option value="">Selecione</option>
                        @foreach($data["freguesias"] as $key=>$value)
                            @if($data["store"]->caop->dicofre== $key)                            
                                <option value="{{ $key}}" selected>{{ $value }}</option>
                            @else      
                            <option value="{{ $key}}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>                        
                </div>
                <div class="form-group">
                    <?php echo Form::label('address', 'Morada'); ?>
                    <input type="text" autocomplete="off" name="address" id="address" class="form-control input-lg" placeholder="Morada" value="{{ $data['store']->address }}" />
                </div>
                
                <div class="form-group form-center">
                    {{Form::hidden('location', $data["store"]->text_location, ['class' => 'form-control', 'id' => 'location'])}}
                    <p><small class="form-note">- Edite a localização da loja no mapa -</small></p>
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
        {{Form::hidden('_method', 'PUT')}}
        {{Form::button('Atualizar', ['class' => 'btn btn-lg btn-primary', 'id' => 'submit-store'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>
