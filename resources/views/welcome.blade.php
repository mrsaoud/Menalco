@extends('layout.app')
@section('main')
{{-- <style>
    .loading{
    display: none;
}

</style> --}}
{{-- <meta name="_token" content="{{ csrf_token() }}"> --}}
@csrf
<div class="page-content" id="all">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleInputUsername1">Scanner le code-barres</label>
                        <input type="text" class="form-control" id="codeBar" autocomplete="off" placeholder="Ex:216821872" value="" maxlength="20">
                    </div>
                    {{-- <div class="form-group">
                                <button type="submit" class="btn btn-primary">rechercher</button>
                            </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="hidden">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-head d-flex p-2">
                    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 button">
                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                        CSV
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive pl-2">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>Code Bar</th>
                                    <th>Designation</th>
                                    <th>Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script>
    $('#hidden').hide();

    $('#link').click(function(evt) {
        $('input#codeBar').removeAttr('value');
        $("input#codeBar").focus();
        evt.preventDefault();
    });



    $(document).ready(function() {

        $('#codeBar').bind('paste', function(e) {

            var pastedData = e.originalEvent.clipboardData.getData('text');
            var codeBarLength = pastedData.length;

            if (codeBarLength === 20) {
                $('#hidden').show(100);

                var table = $('.table').DataTable({
                    "createdRow": function(row, data, dataIndex) {
                        if (data[5] != 0) {
                            $(row).css("background-color", "green");
                            $(row).css("color", "white");
                        } else if (data[5] == 0) {
                            $(row).css("background-color", "red");
                            $(row).css("color", "white");
                        }
                    },
                    destroy: true,
                    oLanguage: {
                        "sSearch": "Code a barees",
                        "paginate": {
                        "first": "Première",
                        "last": "Dernière",
                        "next": "Suivante",
                        "previous": "Précédente"
                    },
                    "zeroRecords": "Aucune entrée correspondante trouvée",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
                    },
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    autoWidth: false,
                    lengthChange: false,
                    pageLength: 500,
                    order: [[2, 'desc']],

                    ajax: {
                        url: '/get-data',
                        type: 'GET',
                        data: function(d) {
                            d.codeBar = pastedData
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                    },
                    columns: [{
                            data: 19,
                            name: 'CodeBar'
                        },
                        {
                            data: 8,
                            name: 'Designation'
                        },
                        {
                            data: 5,
                            name: 'Quantité',
                        },
                    ],
                    "initComplete": function(settings, json) {
                        $('div.dataTables_filter input', table.table().container()).focus();
                    }
                });


                $('.dataTables_filter input').bind('paste', function(e) {
                    var pastedData = e.originalEvent.clipboardData.getData('text');

                    var table = $('.table').DataTable();
                    var row = table.row('#row-' + pastedData).data();

                    var quantite = row[5];
                    var codeBar = row[19];
                    var session = $('#codeBar').val();
                    var prices;
                    Swal.fire({
                        title: 'Combien d\'article?',
                        icon: 'question',
                        input: 'text',
                        didOpen: () => {
                            const inputRange = Swal.getInput()
                            const inputNumber = Swal.getContent()
                                .querySelector('#range-value')
                            inputRange.addEventListener('input', () => {
                                quantite = inputRange.value
                            })
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/update-data?codeBar=' + codeBar + '&q=' + quantite + '&session=' + session,
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': $(
                                        'meta[name="csrf-token"]'
                                    ).attr('content')
                                },

                                success: function() {
                                    var oTable = $('.table').dataTable();
                                    // to reload
                                    oTable.api().ajax.reload();
                                    $('div.dataTables_filter input', table.table().container()).focus();
                                }
                            });

                        } else {
                            Swal.close()
                        }

                    });


                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La session est erronée',
                    text: 'La session est erronée!',
                    showConfirmButton: false,
                    timer: 1300
                })
            }
        });

    });

    $('.button').click(function() {
        var session = $('#codeBar').val();
                $.ajax({
                    url: '/export-data?session='+ session,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $(
                            'meta[name="csrf-token"]'
                        ).attr('content')
                    },

                    success: function() {
                        Swal.fire({
                        icon: 'success',
                        title: 'Les données sont validée',
                        text: 'Les données sont validée!',
                        showConfirmButton: false,
                        timer: 1900
                        });
                    }
                });
            });
</script>
@endsection