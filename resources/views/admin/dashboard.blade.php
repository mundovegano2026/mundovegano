@extends('layouts.admin')

@section('content')

<h2>Painel Principal</h2>

<div class="col-md-4">
    <div class="well text-center">

    <h1>Visitantes do Mundo Vegano</h1>

    <div class="easy-pie-chart txt-color-green easyPieChart" data-percent="100" data-pie-size="120">
        <span class="easy-pie-number">{{ $data["totalVisits"] }}</span>
    </div>

</div>
</div>
<div class="col-md-4">
        <div class="well text-center">

        <h1>Visitantes Registados</h1>

        <div class="easy-pie-chart txt-color-green easyPieChart" data-percent="100" data-pie-size="120">
            <span class="easy-pie-number">{{ $data["totalAuthVisits"] }}</span>
        </div>

    </div>
</div>
<div class="col-md-4">
        <div class="well text-center">

        <h1>Artigos por Visitantes</h1>

        <div class="easy-pie-chart txt-color-green easyPieChart" data-percent="100" data-pie-size="120">
            <span class="easy-pie-number">{{ $data["totalProducts"] }}</span>
        </div>

    </div>
</div>
<div class="col-md-12">
        <div class="well text-center" style="margin-bottom: 100px">

            <h1>Visitantes na Última semana</h1>

            <div class="widget-body no-padding">

                <div id="visitors-chart" class="chart has-legend"></div>

            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
    <script>
        window.visitList = {!! json_encode($data["visits"], JSON_HEX_TAG) !!};
    </script>
    <script src="/js/dashboard.js"></script>
@endpush
