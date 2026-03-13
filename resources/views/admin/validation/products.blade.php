@extends('layouts.admin')

@section('content')

<h2>Produtos por Validar</h2>

    <div class="btn-area">
        <button class="btn btn-warning" onClick="window.location.href='/admin/tasks'"><a href="#" style="text-decoration: none; color: white">Voltar</a></button>
    </div>

    <!-- Products Navigation Buttons -->
    <div class="table-nav-area">
        {{ $data["products"]->links() }}
    </div>
    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false" >
        <header>
            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
            <h2>Registos de Novos Produtos</h2>          
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
                    {{ $data["products"]->total() . " registos encontrados." }}
                </div>
                <div class="table-responsive index-table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data["products"] as $product)
                            <tr>
                                <td style="width: 1%; white-space: nowrap">
                                    <button type="button" class="btn btn-primary editProduct" data-toggle="editRecordModal"  data-id="{{ $product->id }}"  data-post="data-php" data-action="edit">Editar</button>
                                    <!--<button class="btn btn-primary" onClick="editRecord" data-record="">Editar</button>-->
                                </td>
                                <td>{{ $product->name }}</td>
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

    
    <div class="modal fade" id="editRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection

@push('scripts')    

    <!-- Pointer events polyfill for old browsers, see https://caniuse.com/#feat=pointer -->
    <script src="https://unpkg.com/elm-pep"></script>

    <script src="/js/product.js"></script>

@endpush
