@extends('layouts.admin')

@section('content')

<h2>Categorias</h2>

    <div class="btn-area">
        @if(!($data["categories"]->total() > 0 && $data["categories"][0]->level == 1))
            <button class="btn btn-warning" onClick="window.location.href='/admin/categories'"><a href="#" style="text-decoration: none; color: white">Voltar</a></button>
        @endif
        <button class="btn btn-success" data-toggle="modal" data-target="#newRecordModal">Nova Categoria</button>
    </div>

    @if(isset($data["original_category"]))
        {!! Form::open(['action' => ['CategoriesController@update', $data["original_category"]->id], 'class' => 'record-form', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        
            <div class="modal-body">      
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Dados da Categoria</legend>
                            <div class="form-group">
                                {{Form::text('name', $data["original_category"]->name, ['class' => 'form-control', 'placeholder' => 'Nome'])}}
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Imagens</legend>
                            <div class="form-group">         
                                @include('admin.inc.image_upload', ['entity' => 'category', 'no_extra'=>true, 'image_name' => 'main_image', 'images' => $data["original_category"]->images ])
                            </div> 
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Atualizar', ['class' => 'btn btn-primary'])}}
            </div>
        {!! Form::close() !!}
    @endif

    <!-- Categories Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["categories"]->appends($_GET)->links() }}
    </div>
    
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-6 col-md-offset-6">
            <form action="/admin/categories/{{ isset($data['original_category']) ? $data['original_category']->id : ''}}" method="GET" role="search">
                <div class="input-group">
                    <input class="form-control" id="search" name="search" type="text" value="{{ $data['search']}}">
                    <div class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                                Filtrar
                        </button>
                        <button class="btn btn-info" style="margin-left: 5px" type="button" onClick="window.location.href='/admin/categories/{{ isset($data['original_category']) ? $data['original_category']->id : ''}}'">
                                Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false" >
        <header>
            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
            <h2>Registos de Categorias de nível {{ isset($data["original_category"]) ? $data["original_category"]->level + 1 : 1 }}</h2>          
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

                <div class="alert alert-info no-margin fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-info"></i>
                    {{ $data["categories"]->total() . " registos encontrados." }}
                </div>
                <div class="table-responsive index-table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Nome</th>
                                <th>Ativo?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data["categories"] as $category)
                            <tr>
                                <td style="width: 1%; white-space: nowrap">
                                    <button class="btn btn-primary" onClick="window.location.href = '/admin/categories/{{ $category->id }}'">
                                        Editar
                                    </button>
                                </td>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-primary" onClick="window.location.href = '/admin/categories/{{ $category->active ? "inactivate" : "activate" }}/{{ $category->id }}'">
                                        {{ $category->active ? "Inativar" : "Ativar" }}
                                    </button>
                                </td>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-danger deleteCat" data-id="{{ $category->id }}" data-name="{{ $category->name}}">
                                        Eliminar
                                    </button>
                                </td>
                                <td>{{ $category->name }}</td>
                                <td><i class="fa {{ $category->active ? "fa-check-circle" : "fa-times-circle" }}"></i></td>
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
            
    {{-- @if(!($data["categories"]->total() > 0 && $data["categories"][0]->level == 1)) --}}
    <!-- Modal -->
    <div class="modal fade" id="newRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Nova Categoria</h4>
                </div>
                {!! Form::open(['action' => 'CategoriesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    
                    <div class="modal-body">      
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                <legend>Dados da Categoria</legend>
                                    <div class="form-group">
                                        {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Nome'])}}
                                        {{Form::hidden('level', isset($data["original_category"]) ? $data["original_category"]->level + 1 : 1 )}}
                                        {{Form::hidden('parent_id', isset($data["original_category"]) ? $data["original_category"]->id : 0 )}}
                                    </div> 
                                </fieldset>
                                <fieldset>
                                    <legend>Imagem</legend>
                                    <div class="form-group">         
                                        {{-- @include('admin.inc.image_upload', ['entity' => 'category', 'no_extra'=>true, 'image_name' => 'new_main_image', 'images' => isset($data["category"]) ? $data["category"]->images : $data["original_category"]->images ]) --}}
                                        @include('admin.inc.image_upload', ['entity' => 'category', 'no_extra'=>true, 'image_name' => 'new_main_image', 'images' => null ])
                                    </div> 
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar
                        </button>
                        {{Form::submit('Gravar', ['class' => 'btn btn-primary'])}}
                    </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal -->
    <div class="modal fade" id="deleteRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Eliminar Categoria</h4>
                </div>
                {!! Form::open(['action' => 'CategoriesController@delete', 'method' => 'POST','id' => 'category-form']) !!}
                    
                    {{ csrf_field() }}
                    {{ Form::hidden('validation', '', ['id' => 'validation']) }}
                    <div class="modal-body">      
                
                        Confirma a eliminação da categoria <span id="del_cat_name"></span>? Isto vai fazer com que as estatísticas e os artigos associados à categoria também sejam eliminados.
                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
                            Cancelar
                        </button>
                        {{-- {{Form::hidden('_method', 'DELETE')}} --}}
                        <input type="hidden" name="id" id="del_id" />
                        
                        {{Form::submit('Confirmar', ['class' => 'btn btn-lg btn-primary', 'id' => 'delete-category'])}}
                    </div>
                {!! Form::close() !!}
                    
                <div id="overlay" style="display:none;">
                    <div class="spinner"></div>
                    <br/>
                    A Carregar...
                </div>
                
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- @endif --}}
@endsection

@push('scripts')
    <script src="/js/category.js"></script>
@endpush