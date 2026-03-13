@extends('layouts.admin')

@section('content')

<h2>Lojas</h2>

    <div class="btn-area">
        <button type="button" class="btn btn-success editStore" data-toggle="editRecordModal" data-post="data-php" data-action="create">Nova Loja</button>
        {{-- <button class="btn btn-success" data-toggle="modal" data-target="#newRecordModal">Nova Loja</button> --}}
    </div>

    <!-- Stores Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["stores"]->appends($_GET)->links() }}
    </div>
    
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-6 col-md-offset-6">
            <form action="/admin/stores" method="GET" role="search">
                <div class="input-group">
                    <input class="form-control" id="search" name="search" type="text" value="{{ $data['search']}}">
                    <div class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                                Filtrar
                        </button>
                        <button class="btn btn-info" style="margin-left: 5px" type="button" onClick="window.location.href='/admin/stores'">
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
            <h2>Registos de Lojas</h2>          
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
                    {{ $data["stores"]->total() . " registos encontrados." }}
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
                            @foreach($data["stores"] as $store)
                                @if(Request::get('id')=='' || Request::get('id') == $store->id)
                                    <tr>
                                        <td style="width: 1%; white-space: nowrap">
                                            <button type="button" class="btn btn-primary editStore" data-toggle="editRecordModal"  data-id="{{ $store->id }}"  data-coord="{{ $store->text_location }}" data-post="data-php" data-action="edit">Editar</button>
                                            <!--<button class="btn btn-primary" onClick="editRecord" data-record="">Editar</button>-->
                                        </td>
                                        <td style="width: 1%; white-space: nowrap; text-align: center">
                                            <button class="btn btn-primary" onClick="window.location.href = '/admin/stores/{{ $store->active ? "inactivate" : "activate" }}/{{ $store->id }}'">
                                                {{ $store->active ? "Inativar" : "Ativar" }}
                                            </button>
                                        </td>
                                        <td style="width: 1%; white-space: nowrap; text-align: center">
                                            <button class="btn btn-danger editStore" data-toggle="deleteRecordModal" data-id="{{ $store->id }}" data-post="data-php" data-action="delete">
                                                Eliminar
                                            </button>
                                        </td>
                                        <td>{{ $store->name }}</td>
                                        <td><i class="fa {{ $store->active ? "fa-check-circle" : "fa-times-circle" }}"></i></td>
                                    </tr>
                                @endif
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

    <!-- Pointer events polyfill for old browsers, see https://caniuse.com/#feat=pointer -->
    <script src="https://unpkg.com/elm-pep"></script>

    <script src="/js/store.js"></script>

@endpush
