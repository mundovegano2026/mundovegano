@extends('layouts.admin')

@section('content')

<h2>Utilizadores</h2>

    <!-- Users Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["users"]->appends($_GET)->links() }}
    </div>
    
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-6 col-md-offset-6">
            <form action="/admin/users" method="GET" role="search">
                <div class="input-group">
                    <input class="form-control" id="search" name="search" type="text" value="{{ $data['search']}}">
                    <div class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                                Filtrar
                        </button>
                        <button class="btn btn-info" style="margin-left: 5px" type="button" onClick="window.location.href='/admin/users'">
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
            <h2>Registos de Utilizadores</h2>          
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
                    {{ $data["users"]->total() . " registos encontrados." }}
                </div>
                <div class="table-responsive index-table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data["users"] as $user)
                            <tr>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-danger editUser" data-toggle="deleteRecordModal" data-id="{{ $user->id }}" data-post="data-php" data-action="delete">
                                        Eliminar
                                    </button>
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
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
    <script src="/js/user.js"></script>
@endpush
