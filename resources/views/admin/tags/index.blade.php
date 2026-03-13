@extends('layouts.admin')

@section('content')

<h2>Tags</h2>

    <div class="btn-area">
        <button type="button" class="btn btn-success editTag" data-toggle="modal" data-post="data-php" data-action="create">Nova Tag</button>
    </div>

    <!-- Tags Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["tags"]->appends($_GET)->links() }}
    </div>
    
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-6 col-md-offset-6">
            <form action="/admin/tags" method="GET" role="search">
                <div class="input-group">
                    <input class="form-control" id="search" name="search" type="text" value="{{ $data['search']}}">
                    <div class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                                Filtrar
                        </button>
                        <button class="btn btn-info" style="margin-left: 5px" type="button" onClick="window.location.href='/admin/tags'">
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
            <h2>Registos de Tags</h2>          
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
                    {{ $data["tags"]->total() . " registos encontrados." }}
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
                            @foreach($data["tags"] as $tag)
                            <tr>
                                <td style="width: 1%; white-space: nowrap">
                                    <button type="button" class="btn btn-primary editTag" data-toggle="editRecordModal"  data-id="{{ $tag->id }}" data-post="data-php" data-action="edit">Editar</button>
                                    <!--<button class="btn btn-primary" onClick="editRecord" data-record="">Editar</button>-->
                                </td>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-primary" onClick="window.location.href = '/admin/tags/{{ $tag->active ? "inactivate" : "activate" }}/{{ $tag->id }}'">
                                        {{ $tag->active ? "Inativar" : "Ativar" }}
                                    </button>
                                </td>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-danger editTag" data-toggle="deleteRecordModal" data-id="{{ $tag->id }}" data-post="data-php" data-action="delete">
                                        Eliminar
                                    </button>
                                </td>
                                <td>{{ $tag->name }}</td>
                                <td><i class="fa {{ $tag->active ? "fa-check-circle" : "fa-times-circle" }}"></i></td>
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
				
    <!-- Modal -->
    <div class="modal fade" id="newRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    
    <div class="modal fade" id="editRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="deleteRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@push('scripts')
    <script src="/js/tag.js"></script>
@endpush
