@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-block">
            <a class="close" data-dismiss="alert" href="#">×</a>
            {{ $error }}
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert alert-success alert-block">
        <a class="close" data-dismiss="alert" href="#">×</a>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-block">
    <a class="close" data-dismiss="alert" href="#">×</a>
    {{ session('error') }}
</div>
@endif