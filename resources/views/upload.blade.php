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
                        <label for="col-md-8 stretch-card" class="col-sm-3 col-form-label">UPLOAD FICHIER CSV</label>
                        <div class="col-md-8 stretch-card" >
                            <input name="CSV" type="file" id="myDropify" class="border" class="dropify" data-height="100"/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        @if(session('message'))
            const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
            })

            Toast.fire({
            icon: 'error',
            title: '{{session('message')}}'
            })
        @endif
    </script>
@endsection