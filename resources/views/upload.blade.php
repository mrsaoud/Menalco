@extends('layout.app')
@section('main')
@csrf
<div class="page-content" id="all">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">UPLOAD FICHIER CSV</label>
                        <div class="col-md-8 stretch-card">
                            <input name="CSV" type="file" id="myDropify" class="border" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
