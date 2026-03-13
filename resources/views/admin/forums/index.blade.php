@extends('layouts.admin')

@section('content')

<h2>Fórum</h2>
    <?php $newEntityTitle = "Novo Tema"; ?>
    <div class="btn-area">
        @if(isset($data["original_board"]) || isset($data["original_post"]))
            <button class="btn btn-warning" onClick="window.location.href='/admin/forums'"><a href="#" style="text-decoration: none; color: white">Voltar</a></button>
        @endif
        @if(!isset($data["original_board"]) && !isset($data["original_post"]))
            <button class="btn btn-success" data-toggle="modal" data-target="#newRecordModal">Novo Tema</button>
        @elseif(isset($data["original_board"]))
            <?php $newEntityTitle = 'Nova Publicação'; ?>
            <button class="btn btn-success" data-toggle="modal" data-target="#newRecordModal">Nova Publicação</button>
        @else
        <?php $newEntityTitle = 'Novo Comentário'; ?>
            <button class="btn btn-success" data-toggle="modal" data-target="#newRecordModal">Novo Comentário</button>
        @endif
        
    </div>

    @if(isset($data["original_board"]))
        {!! Form::open(['action' => ['ForumsController@update', $data["original_board"]->id], 'class' => 'record-form', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        
            <div class="modal-body">      
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::text('name', $data["original_board"]->name, ['class' => 'form-control', 'placeholder' => 'Nome'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Atualizar', ['class' => 'btn btn-primary'])}}
            </div>
        {!! Form::close() !!}
    @endif

    @if(isset($data["original_post"]))
        {!! Form::open(['action' => ['ForumsController@updatePost', $data["original_post"]->id], 'class' => 'record-form', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        
            <div class="modal-body">      
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::label('title', 'Título')}}
                            {{Form::text('title', $data["original_post"]->title, ['class' => 'form-control', 'placeholder' => 'Título'])}}
                         </div>
                         <div class="form-group">
                            {{Form::label('body', 'Texto')}}
                            {{Form::textarea('body', $data["original_post"]->body, ['class' => 'form-control', 'placeholder' => 'Texto'])}}
                          </div>
                    </div>   
                        
                </div>
            </div>
            <div class="modal-footer">
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Atualizar', ['class' => 'btn btn-primary'])}}
            </div>
        {!! Form::close() !!}
    @endif

    <!-- Boards Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["forums"]->appends($_GET)->links() }}
    </div>

    @if(!isset($data["comment"]))
    
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-6 col-md-offset-6">
                <form action="/admin/forums/{{ isset($data['original_board']) ? $data['original_board']->id : (isset($data['original_post']) ? 'posts/' . $data['original_post']->id : '') }}" method="GET" role="search">
                    <div class="input-group">
                        <input class="form-control" id="search" name="search" type="text" value="{{ $data['search']}}">
                        <div class="input-group-btn">
                            <button class="btn btn-info" type="submit">
                                    Filtrar
                            </button>
                            <button class="btn btn-info" style="margin-left: 5px" type="button" onClick="window.location.href='/admin/forums/{{ isset($data['original_board']) ? $data['original_board']->id : (isset($data['original_post']) ? 'posts/' . $data['original_post']->id : '') }}'">
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
                <h2>{{ isset($data["original_post"]) ? 'Registos de Comentários na Publicação' : (isset($data["original_board"]) ? 'Registos de Publicações no Tema' : 'Registos de Temas do Fórum') }}</h2>          
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
                                    <th></th>
                                    <th>{{ isset($data["original_board"]) || isset($data["original_post"]) ? 'Título' : 'Nome' }}</th>
                                    <th>Ativo?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data["forums"] as $forum)
                                <tr>
                                    <td style="width: 1%; white-space: nowrap">
                                    {{-- <button class="btn btn-primary" {{ !isset($data["original_board"]) ? 'onClick=window.location.href=\'/admin/forums/' . $forum->id . "'":""}}> --}}
                                        <button class="btn btn-primary" onClick="window.location.href='/admin/forums/{{ (isset($data["original_post"]) ? 'comments/' : ( isset($data["original_board"]) ? 'posts/' : 'boards/' )) . $forum->id  }}'" >
                                            Editar
                                        </button>
                                    </td>
                                    <td style="width: 1%; white-space: nowrap; text-align: center">
                                        <button class="btn btn-primary" onClick="window.location.href = '/admin/forums/{{ $forum->active ? "inactivate" : "activate" }}/{{ $forum->id }}'">
                                            {{ $forum->active ? "Inativar" : "Ativar" }}
                                        </button>
                                    </td>
                                    <td>{{ isset($data["original_board"]) || isset($data["original_post"]) ? $forum->title : $forum->name }}</td>
                                    <td><i class="fa {{ $forum->active ? "fa-check-circle" : "fa-times-circle" }}"></i></td>
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
    @endif
    
    <!-- Modal -->
    <div class="modal fade" id="newRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ $newEntityTitle }}</h4>
                </div>
                {!! Form::open(['action' => 'ForumsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    
                    <div class="modal-body">      
                        <div class="row">
                            <div class="col-md-12">
                                @if(!isset($data['original_board']) && !isset($data['original_post']))
                                <div class="form-group">
                                    {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Nome'])}}
                                    {{Form::hidden('original_board', "")}}
                                </div>
                                @endif
                                @if(isset($data['original_board']) || isset($data['original_post']))
                                <div class="form-group">
                                    {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Título'])}}
                                    {{Form::hidden('original_board', isset($data['original_board']) ? $data['original_board']->id : "")}}
                                    {{Form::hidden('original_post', isset($data['original_post']) ? $data['original_post']->id : "")}}
                                </div>
                                <div class="form-group">
                                    {{Form::textarea('body', '', ['class' => 'form-control', 'placeholder' => 'Texto'])}}
                                </div>
                                @endif
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
@endsection