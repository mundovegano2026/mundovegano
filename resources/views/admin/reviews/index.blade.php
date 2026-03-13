@extends('layouts.admin')

@section('content')

<h2>Avaliações por Utilizadores</h2>

    <!-- Reviews Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["reviews"]->appends($_GET)->links() }}
    </div>
    
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-6 col-md-offset-6">
            <form action="/admin/reviews" method="GET" role="search">
                <div class="input-group">
                    <input class="form-control" id="search" name="search" type="text" value="{{ $data['search']}}">
                    <div class="input-group-btn">
                        <button class="btn btn-info" type="submit">
                                Filtrar
                        </button>
                        <button class="btn btn-info" style="margin-left: 5px" type="button" onClick="window.location.href='/admin/reviews'">
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
            <h2>Registos de Avaliações</h2>          
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
                    {{ $data["reviews"]->total() . " registos encontrados." }}
                </div>
                <div class="table-responsive index-table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Artigo</th>
                                <th>Utilizador</th>
                                <th>Avaliação</th>
                                <th>Ativo?</th>
                                <th>Validação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data["reviews"] as $review)
                            <tr>
                                <td style="width: 1%; white-space: nowrap">
                                    <button type="button" class="btn btn-primary editReview" data-toggle="editRecordModal"  data-id="{{ $review->id }}" data-post="data-php" data-action="edit">Editar</button>
                                    <!--<button class="btn btn-primary" onClick="editRecord" data-record="">Editar</button>-->
                                </td>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-primary" onClick="window.location.href = '/admin/reviews/{{ $review->active ? "inactivate" : "activate" }}/{{ $review->id }}'">
                                        {{ $review->active ? "Inativar" : "Ativar" }}
                                    </button>
                                </td>
                                <td style="width: 1%; white-space: nowrap; text-align: center">
                                    <button class="btn btn-danger editReview" data-toggle="deleteRecordModal" data-id="{{ $review->id }}" data-post="data-php" data-action="delete">
                                        Eliminar
                                    </button>
                                </td>
                                <td>{{ isset($review->product) ? $review->product->name : 'Sem produto' }}</td>
                                <td>{{ isset($review->user) ? $review->user->name : 'Anónimo' }}</td>
                                <td>@include('admin.inc.form-elements.rating', ['title' => '', 'score' => $review->score, 'no_score_message' => 'Sem avaliação', 'small' => true])</td>
                                <td><i class="fa {{ $review->active ? "fa-check-circle" : "fa-times-circle" }}"></i></td>
                                <td><i class="fa {{ $review->status->type == "Validado" ? "fa-check-circle" : ($review->status->type == "Rejeitado" ? "fa-times-circle" : "fa-pause-circle") }}"></i></td>
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
    <script src="/js/review.js"></script>
@endpush
