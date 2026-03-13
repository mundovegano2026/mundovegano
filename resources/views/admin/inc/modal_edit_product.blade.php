<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">Editar Produto</h4>
</div>
{!! Form::open(['action' => ['ProductsController@update', $data["product"]->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'product-form']) !!}
    
    {{ csrf_field() }}
    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
    <div class="modal-body">      


        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget tabs-widget" id="wid-id-tabs" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <header>
                <ul class="nav nav-tabs pull-left in">

                    <li class="active">

                        <a data-toggle="tab" href="#hr1"> <span class="hidden-mobile hidden-tablet"> Estado</span> </a>

                    </li>

                    <li>
                        <a data-toggle="tab" href="#hr2"> <span class="hidden-mobile hidden-tablet"> Dados Gerais</span> </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#hr3"> <span class="hidden-mobile hidden-tablet"> Lojas</span> </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#hr4"> <span class="hidden-mobile hidden-tablet"> Imagens</span> </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#hr5"> <span class="hidden-mobile hidden-tablet"> Outras Info</span> </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#hr6"> <span class="hidden-mobile hidden-tablet"> Avaliações</span> </a>
                    </li>

                </ul>
            </header>

            <!-- widget div-->
            <div class="tabs-parent">

                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->

                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">

                    <div class="tab-content">
                        <div class="tab-pane active" id="hr1">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">    
                                        <?php echo Form::label('image', 'Estado do Artigo'); ?>  
                                        <select name="status" class="form-control status_select input-lg" required="required" data-desc="estado">
                                            <option value="">Selecione</option>
                                            @foreach($data["statuses"] as $status)
                                                <option <?php if($status->id == $data["status"]->id) { echo "selected"; }?> value="{{$status->id}}">{{ $status->type }}</option>
                                            @endforeach
                                        </select>                                
                                    </div> 
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="hr2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php echo Form::label('name', 'Nome'); ?>
                                        {{
                                            Form::text(
                                                'name', 
                                                $data["product"]->name, 
                                                ['class' => 'form-control input-lg', 'placeholder' => 'Nome', 'required' => 'required', 'data-desc' => 'nome'])
                                        }}
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('brand', 'Marca'); ?>
                                        <input type="text" autocomplete="off" name="brand" id="brand" class="form-control input-lg" placeholder="Marca" value="{{ isset($data["product"]->brand) ? $data["product"]->brand->name : '' }}" />
                                        <div id="brandList" class="suggestion-list"></div>
                                    </div>                         
                                    <div class="form-group">             
                                        <?php echo Form::label('category', 'Categoria'); ?>
                                        <?php $cat_index = 1; ?>
                                        @foreach($data["product_categories"] as $categoryInfo)
                                            <div class="form-group">    
                                                <select <?php if($cat_index == count($data["product_categories"])) { echo 'name="category"'; }?> pos="{{ $cat_index++ }}" class="form-control category_select input-lg" required="required" data-desc="categoria">
                                                    <option value="">Selecione</option>
                                                    @foreach($categoryInfo["list"] as $category)
                                                        <option <?php if($category->id == $categoryInfo["selected"]->id) { echo "selected"; }?> value="{{$category->id}}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>                                
                                            </div>
                                        @endforeach 
                                    </div>    
                                    <div class="form-group">  
                                        @include('admin.inc.form-elements.input_select', ['type' => 'decimal', 'values' => ['text' => $data["product"]->capacity, 'select_text' => isset($data['product']->capacity_unit) ? $data['product']->capacity_unit->symbol : '', 'select' => isset($data['product']->capacity_unit) ? $data['product']->capacity_unit->symbol : ''], 'input' => ['name' => 'capacity', 'placeholder' => 'capacidade'], 'select' => ['name' => 'capacity_unit', 'placeholder' => 'Selecionar', 'options' => $data['capacity_unit_list'], 'option_value' => 'symbol']])                                       
                                    </div>     
                                </div>
                            </div>                              
                        </div>
                        <div class="tab-pane" id="hr3">
                            {{-- STORES --}}
                            <p><?php echo Form::label('store', 'Adicionar Loja'); ?></p>
                            <div class="input-group">
                                <input type="text" autocomplete="off" id="store" class="form-control input-lg" placeholder="Nome da Loja" />
                                <span class="input-group-addon">-</span>
                                <input type="text" autocomplete="off" id="price" class="form-control input-lg" placeholder="Preço" />
                                <button type="button" id="add-store" class="form-control-feedback btn btn-primary">Adicionar</button>
                            </div>
                            <div id="storeList" class="suggestion-list"></div>
                            {{ Form::hidden('stores', $data["stores"], ['id' => 'stores'] ) }}
                            <div class="form-group store-form">
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
                                                            @foreach($data["uniqueStores"] as $store)
                                                                <tr data-store="{{ $store->name}}">
                                                                    <td style="width:1px;cursor:pointer;"><i class='fa fa-times remove-icon'></i></td>
                                                                    <td><a href='stores?id={{ $store->id }}&search={{ $store->name }}' target='_blank'>{{ $store->name }}</a></td>
                                                                    <td>{{ $store->caop->concelho }}</td>
                                                                    <td>{{ $store->pivot->price != 0 ? $store->pivot->price : "-" }}</td>
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
                        </div>
                        <div class="tab-pane" id="hr4">
                            <div class="form-group">                  
                                @include('admin.inc.image_upload', ['image_name' => 'main_image', 'images' => $data["product"]->images])
                            </div> 
                        </div>
                        <div class="tab-pane" id="hr5">
                            <div class="form-group">
                                <?php echo Form::label('obs', 'Observações'); ?>  
                                {{
                                    Form::textarea(
                                        'obs', 
                                        $data["product"]->obs, 
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
                                @include('admin.inc.form-elements.tag-input', ['name' => 'tag-input', 'tags' => $data['tags'], 'value' => implode(";", array_column($data["product"]->tags->toArray(), 'name')) ])
                                <input type="hidden" id="tags" name="tags" class="form-control">
                            </div>
                        </div>
                        <div class="tab-pane" id="hr6">
                            <div class="well">

                                <table class="table table-striped table-forum">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                @include('admin.inc.form-elements.rating', ['title' => 'Pontuação Média:', 'text_score' => true, 'score' => floor($data["product"]->reviews->avg('score') * 2) / 2, 'no_score_message' => 'Sem avaliações'])
                                            </th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($data["product"]->reviews as $review)
                                            <?php $userReviewCount = isset($review->user) ? $review->user->reviews->count() : 0; ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php if(isset($review->user)) { ?>
                                                        <a href="#"><strong>{{ $review->user->name}}</strong><p><small>({{ $userReviewCount . ($userReviewCount > 1 ? ' avaliações' : ' avaliação') }})</small></p></a>
                                                    <?php } else { ?>
                                                        <strong>Anónimo</strong>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    a <em>{{ $review->created_at }}</em>
                                                    <span class="pull-right">
                                                        @include('admin.inc.form-elements.rating', ['title' => '', 'score' => $review->score, 'no_score_message' => 'Sem avaliação', 'small' => true])
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" style="width: 25%;">
                                                    <?php if(isset($review->user)) { ?>
                                                        <div class="push-bit">
                                                            <i title="Marcar ação positiva" data-review_id="{{ $review->id}}" data-type="up" class="review-action fa fa-thumbs-up {{ $review->up_score ? 'selected' : '' }}"></i>
                                                            <i title="Marcar ação negativa" data-review_id="{{ $review->id}}" data-type="down" class="review-action fa fa-thumbs-down {{ $review->down_score ? 'selected' : '' }}"></i>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    {{ $review->comment != "" ? $review->comment : "- O utilizador não deixou um comentário. -" }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- end Post -->
    
                                    </tbody>
                                </table>
    
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end tabs content -->

            </div>
            <!-- end tabs div -->

        </div>
        <!-- end tabs widget -->


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
            Cancelar
        </button>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::button('Gravar', ['class' => 'btn btn-lg btn-primary', 'id' => 'submit-product'])}}
    </div>
{!! Form::close() !!}
    
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    A Carregar...
</div>

{{-- @push('scripts')    

    <script src="/js/brand.js"></script>

@endpush --}}
