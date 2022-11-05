@extends('layout.app')
@section('main')
<style>
    .float{
	position:fixed;
	width:40px;
	height:40px;
	bottom:40px;
	right:25px;
	background-color:#727cf5;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;
}

.my-float{
	margin-top:20%;
}
</style>
@csrf
<div class="page-content" id="all">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleInputUsername1">Scanner le code-barres</label>
                        <input type="text" class="form-control" id="codeBar" autocomplete="off" placeholder="Ex:216821872" value="{{ Session::get('this')}}" maxlength="20">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="hidden">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-head d-flex p-2 justify-content-center">
                </div>
                <div class="card-body">
                    <div class="table-responsive pl-2">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th class="text-truncate" style="max-width: 50px;">Code Barres</th>
                                    <th class="text-truncate" style="max-width: 50px;">Designation</th>
                                    <th class="text-truncate" style="max-width: 50px;">Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex p-2 justify-content-center">
                    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 button">
                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                        Exporter CSV
                    </button>
                </div>
                <a href="#" class="float" id="spnbottom">
                    <i class="my-float"  data-feather="arrow-down"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

<script>
//afficher le message de fichier ajouter avec success appré la redirection de puis la page upload
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
    icon: 'success',
    title: '{{session("message")}}'
    })
@endif

    $('#hidden').hide();
    $("input#codeBar").focus();
    $('#link').click(function(evt) {
        $('input#codeBar').removeAttr('value');
        $("input#codeBar").focus();
        evt.preventDefault();
    });




    //charger la datatable si il y a une valeur dans le input de session (si la session et déja sauvgarder)
    $(document).ready(function() {
        var pastedData = $('#codeBar').val();
        $("input#codeBar").focus();
        if($('#codeBar').val() != null && pastedData.length == 20){
            $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
                    Swal.fire({
                    icon: 'error',
                    title: "La session n'existe pas",
                    text: "La session n'existe pas",
                    showConfirmButton: false,
                    timer: 1300
                    });
                    };
                    //afficher la card qui contien le tableau
                $('#hidden').show(100);
                var table = $('.table').DataTable({
                    //pour char row crée le script vérifier si la column quantité =0 ou !-0
                    "createdRow": function(row, data, dataIndex) {
                        if (data[5] != 0) {
                            $(row).css("background-color", "green");
                            $(row).css("color", "white");
                        } else if (data[5] == 0) {
                            $(row).css("background-color", "red");
                            $(row).css("color", "white");
                        }
                    },
                    //la possibiliter de régénérer  le tableau plusieur foix
                    destroy: true,
                    oLanguage: {
                        "sSearch": "Code à barres",
                    },
                    processing: true,
                    serverSide: false,
                    bPaginate: false,
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
                    },
                    

                });






                //code a barres coller dans le champe de recherche 
                $('.dataTables_filter input').bind('paste', function(e) {

                    //avoir le text coller de puis la clipboard
                    var pastedData = e.originalEvent.clipboardData.getData('text');
                    var table = $('.table').DataTable();
                    var row = table.row('#row-' + pastedData).data(); 
                    //compare le text coller avec chaque ligne 
                    var filteredData = table.column( 0 ).data()
                                        .filter( function ( value, index ) {
                                            return value == pastedData ? true : false;
                                        } );
                    //si true
                    if(filteredData.length == 1){

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
                                    // actualiser le tableau
                                    oTable.api().ajax.reload();
                                    $('div.dataTables_filter input', table.table().container()).focus();
                                }
                            });

                        } else {
                            Swal.close()
                        }

                    });

                }else{
                    //compare le text coller avec chaque ligne = false
                    Swal.fire({
                    icon: 'error',
                    title: 'Le Code à barres est erronée',
                    text: 'Le Code à barres est erronée!',
                    showConfirmButton: false,
                    timer: 1300
                    });
                    $('.dataTables_filter input').removeAttr('value');
                }
                });
        
        
        }









        //charger la datatable si une valeur correct et coller ou une valeur ecrit aprés entrée
        $('#codeBar').bind('paste keypress', function(e) {
            var pastedData = $('#codeBar').val();
            $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
                    Swal.fire({
                    icon: 'error',
                    title: "La session n'existe pas",
                    text: "La session n'existe pas",
                    showConfirmButton: false,
                    timer: 1300
                    });
                    };
                $('#hidden').show(100);
            if(e.which == 13 || e.originalEvent.clipboardData!= null){
            if(e.originalEvent.clipboardData!= null){
                var pastedData = e.originalEvent.clipboardData.getData('text');
            }else{
                var pastedData = $('#codeBar').val();
            }

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
                        "sSearch": "Code à barres",
                    },
                    processing: true,
                    serverSide: false,
                    bPaginate: false,
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
                    var filteredData = table.column( 0 ).data()
                                        .filter( function ( value, index ) {
                                            return value == pastedData ? true : false;
                                        } );
                    if(filteredData.length == 1){

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

                }else{

                    Swal.fire({
                    icon: 'error',
                    title: 'Le Code à barres est erronée',
                    text: 'Le Code à barres est erronée!',
                    showConfirmButton: false,
                    timer: 1300
                    });
                    $('.dataTables_filter input').removeAttr('value');
                }
                });
               
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La session est erronée',
                    text: 'La session est erronée!',
                    showConfirmButton: false,
                    timer: 1300
                });
                $.ajax({
                    url: '/clear-session',
                    method: 'GET',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });

            }
        }
        });
    

    });




    // sauvgarder le array et exporter comme csv
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

            //button pour balayer vers le bas

            $('#spnbottom').on("click", function () {
                var percentageToScroll = 100;
                var percentage = percentageToScroll / 100;
                var height = $(document).height() - $(window).height();
                var scrollAmount = height * percentage;
                console.log('scrollAmount: ' + scrollAmount);
                jQuery("html, body").animate({
                    scrollTop: scrollAmount
                }, 900);
            });
</script>
@endsection