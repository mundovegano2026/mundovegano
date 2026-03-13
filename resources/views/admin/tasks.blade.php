@extends('layouts.admin')

@section('content')

<h2>Tarefas</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="well">
                <div class="row">
                    <h2 class="well-card-title">Produtos</h2>
                    <hr>
                    <div class="col-md-6">
                        <h5>Por Validar</h5>
                        <p class="h0"><a href="#" onClick="window.location.href='/admin/validation/products'">{{ $data["productsToValidate"] }}</a></p>
                        <h5>Total</h5>
                        <p class="h0">{{ $data["totalProducts"] }}</p>
                    </div>
                    <div class="col-md-6" style="padding-top: 30px">
                        <?php $chartColor = $data["percentageProducts"] > 30 ? ($data["percentageProducts"] > 80 ? 'green' : 'yellow') : 'red'?>
                        <div class="easy-pie-chart txt-color-{{$chartColor}} easyPieChart" data-percent="{{ $data["percentageProducts"] }}" data-pie-size="150">
                            <span class="percent percent-sign txt-color-blue font-xl semi-bold">{{ $data["percentageProducts"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well">
                <div class="row">
                    <h2 class="well-card-title">Lojas</h2>
                    <hr>
                    <div class="col-md-6">
                        <h5>Por Validar</h5>
                        <p class="h0"><a href="#" onClick="window.location.href='/admin/validation/stores'">{{ $data["storesToValidate"] }}</a></p>
                        <h5>Total</h5>
                        <p class="h0">{{ $data["totalStores"] }}</p>
                    </div>
                    <div class="col-md-6" style="padding-top: 30px">
                        <?php $chartColor = $data["percentageStores"] > 30 ? ($data["percentageStores"] > 80 ? 'green' : 'yellow') : 'red'?>
                        <div class="easy-pie-chart txt-color-{{$chartColor}} easyPieChart" data-percent="{{ $data["percentageStores"] }}" data-pie-size="150">
                            <span class="percent percent-sign txt-color-blue font-xl semi-bold">{{ $data["percentageStores"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well">
                <div class="row">
                    <h2 class="well-card-title">Marca</h2>
                    <hr>
                    <div class="col-md-6">
                        <h5>Por Validar</h5>
                        <p class="h0"><a href="#" onClick="window.location.href='/admin/validation/brands'">{{ $data["brandsToValidate"] }}</a></p>
                        <h5>Total</h5>
                        <p class="h0">{{ $data["totalBrands"] }}</p>
                    </div>
                    <div class="col-md-6" style="padding-top: 30px">
                        <?php $chartColor = $data["percentageBrands"] > 30 ? ($data["percentageBrands"] > 80 ? 'green' : 'yellow') : 'red'?>
                        <div class="easy-pie-chart txt-color-{{$chartColor}} easyPieChart" data-percent="{{ $data["percentageBrands"] }}" data-pie-size="150">
                            <span class="percent percent-sign txt-color-blue font-xl semi-bold">{{ $data["percentageBrands"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="well">
                <div class="row">
                    <h2 class="well-card-title">Preços</h2>
                    <hr>
                    <div class="col-md-6">
                        <h5>Por Validar</h5>
                        <p class="h0"><a href="#" onClick="window.location.href='/admin/prices'">{{ $data["pricesToValidate"] }}</a></p>
                        <h5>Total</h5>
                        <p class="h0">{{ $data["totalPrices"] }}</p>
                    </div>
                    <div class="col-md-6" style="padding-top: 30px">
                        <?php $chartColor = $data["percentagePrices"] > 30 ? ($data["percentagePrices"] > 80 ? 'green' : 'yellow') : 'red'?>
                        <div class="easy-pie-chart txt-color-{{$chartColor}} easyPieChart" data-percent="{{ $data["percentagePrices"] }}" data-pie-size="150">
                            <span class="percent percent-sign txt-color-blue font-xl semi-bold">{{ $data["percentagePrices"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <div class="col-md-4">
            <div class="well">
                <div class="row">
                    <h2 class="well-card-title">Denúncias</h2>
                    <hr>
                    <div class="col-md-6">
                        <h5>Por Validar</h5>
                        <p class="h0"><a href="#" onClick="window.location.href='/admin/product_reports'">{{ $data["productReportsToValidate"] }}</a></p>
                        <h5>Total</h5>
                        <p class="h0">{{ $data["totalProductReports"] }}</p>
                    </div>
                    <div class="col-md-6" style="padding-top: 30px">
                        <?php $chartColor = $data["percentageProductReports"] > 30 ? ($data["percentageProductReports"] > 80 ? 'green' : 'yellow') : 'red'?>
                        <div class="easy-pie-chart txt-color-{{$chartColor}} easyPieChart" data-percent="{{ $data["percentageProductReports"] }}" data-pie-size="150">
                            <span class="percent percent-sign txt-color-blue font-xl semi-bold">{{ $data["percentageProductReports"] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>

@endsection