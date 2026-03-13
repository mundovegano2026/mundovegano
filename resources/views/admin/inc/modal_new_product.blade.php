<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Novo Produto</h4>
</div>
{!! Form::open(['action' => ['ProductsController@store', $data["product"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'product-form']) !!}
    
    {{ csrf_field() }}
    <div class="modal-body">      
        <div class="row">
            <div class="col-md-12">
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
                    <?php echo Form::label('brand', 'Marca'); ?>
                    <input type="text" autocomplete="off" name="brand" id="brand" class="form-control input-lg" placeholder="Marca" />
                    <div id="brandList" class="suggestion-list"></div>
                </div>
                <div class="form-group">                      
                    <?php echo Form::label('category', 'Categoria'); ?>
                    <select name="category" pos="1" class="form-control category_select" required="required" data-desc="categoria">
                        <option value="">Selecione</option>
                        @foreach($data["categories"] as $key=>$value)
                            <option value="{{$key}}">{{ $value }}</option>
                        @endforeach
                    </select>                                           
                </div>
                <div class="form-group">  
                    @include('admin.inc.form-elements.input_select', ['type' => 'decimal', 'input' => ['name' => 'capacity', 'placeholder' => 'capacidade'], 'select' => ['name' => 'capacity_unit', 'placeholder' => 'Selecionar', 'options' => $data['capacity_unit_list'], 'option_value' => 'symbol']])                                       
                </div>
            </fieldset>
            <fieldset>
                <legend>Lojas</legend>
                <p><?php echo Form::label('store', 'Adicionar Loja'); ?></p>
                <div class="input-group">
                    <input type="text" autocomplete="off" id="store" class="form-control input-lg" placeholder="Nome da Loja" />
                    <span class="input-group-addon">-</span>
                    <input type="text" autocomplete="off" id="price" class="form-control input-lg" placeholder="Preço" />
                    <button type="button" id="add-store" class="form-control-feedback btn btn-primary">Adicionar</button>
                </div>
                <div id="storeList" class="suggestion-list"></div>
                {{ Form::hidden('stores', '', ['id' => 'stores'] ) }}
                <div class="form-group">
                        <!-- Store Table-->
                        <div class="jarviswidget jarviswidget-color-darken add-record-table" id="wid-id-store" data-widget-editbutton="false" >
                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>Lojas onde o produto está disponível</h2>          
                            </header>

                            <!-- widget div-->
                            <div>

                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->

                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                    <div class="table-responsive index-table">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nome</th>
                                                    <th>Concelho</th>
                                                    <th>Preço</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
                </div>











                {{-- CHAINS --}}
                <p><?php echo Form::label('chain', 'Adicionar Cadeia'); ?></p>
                <div class="input-group">
                    <input type="text" autocomplete="off" id="chain" class="form-control input-lg" placeholder="Nome da Cadeia" />
                    <span class="input-group-addon">-</span>
                    <input type="text" autocomplete="off" id="chain-price" class="form-control input-lg" placeholder="Preço" />
                    <button type="button" id="add-chain" class="form-control-feedback btn btn-primary">Adicionar</button>
                </div>

                <div id="chainList" class="suggestion-list"></div>
                {{ Form::hidden('chains', $data["chains"], ['id' => 'chains'] ) }}
                <div class="form-group chain-form">
                        <!-- Chain Table-->
                        <div class="jarviswidget jarviswidget-color-darken add-record-table" id="wid-id-chain" data-widget-editbutton="false" >
                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>Cadeias onde o produto está disponível</h2>          
                            </header>
    
                            <!-- widget div-->
                            <div>
    
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
    
                                </div>
                                <!-- end widget edit box -->
    
                                <!-- widget content -->
                                <div class="widget-body no-padding">
                                    <div class="table-responsive index-table">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nome</th>
                                                    <th>Preço</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data["uniqueChains"] as $chain)
                                                    <tr data-chain="{{ $chain->name}}">
                                                        <td style="width:1px;cursor:pointer;"><i class='fa fa-times remove-icon'></i></td>
                                                        <td><a href='chains?id={{ $chain->id }}&search={{ $chain->name }}' target='_blank'>{{ $chain->name }}</a></td>
                                                        <td>{{ $chain->pivot->price > 0 ? $chain->pivot->price : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
                </div>



















            </fieldset>
            <fieldset>
                <legend>Imagens</legend>
                <div class="form-group">               
                    @include('admin.inc.image_upload', ['image_name' => 'main_image', 'images' => $data["product"]->images])
                </div> 
            </fieldset>
            <fieldset>
                <legend>Outras Informações</legend>
                <div class="form-group">
                    <?php echo Form::label('obs', 'Outras Informações'); ?>
                    {{
                        Form::textarea(
                            'obs', 
                            '', 
                            ['class' => 'form-control', 'placeholder' => 'Observações sobre o produto'])
                    }}
                    <script>
                        $(document).ready(function() {

                            $('#obs').trumbowyg();
                        });
                    </script>
                </div>

                <div class="form-group">
                    <?php echo Form::label('obs', 'Tags'); ?>
                    @include('admin.inc.form-elements.tag-input', ['name' => 'tag-input', 'tags' => $data['tags'], 'value' => ''])
                    <input type="hidden" id="tags" name="tags" class="form-control">
                </div>
                
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::button('Gravar', ['class' => 'btn btn-lg btn-primary', 'id' => 'submit-product'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>

