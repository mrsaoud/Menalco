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
                    <div class="">
                        <label class="form-label">Sélectionner une session</label>
                        <select class="js-example-basic-single form-select" data-width="100%">
                            <option value="test">test</option>
                            <option value="alcool">alcool</option>
                            <option value="tabac">tabac</option>
                            <option value="vin">vin</option>
                            <option value="whisky">whisky</option>
                            <option value="biere">biere</option>
                            <option value="champane">champagne</option>
                            <option value="vodka">vodka</option>
                            <option value="alimentation">alimentation</option>
                            <option value="vins-rouge">vins-rouge</option>
                            <option value="apperitif">apperitif</option>
                            <option value="gin">gin</option>
                            <option value="pastis">pastis</option>
                            <option value="bier-du-monde">bier-du-monde</option>
                            <option value="cognac-&-brandy">cognac-&-brandy</option>
                            <option value="vins-rose">vins-rose</option>
                            <option value="vins-blanc">vins-blanc</option>
                            <option value="vins-gris">vins-gris</option>
                            <option value="vins-du-monde">vins-du-monde</option>
                            <option value="boissons-soft">boissons-soft</option>
                            <option value="epicerie">epicerie</option>
                            <option value="bier-du-maroc">bier-du-maroc</option>


                            {{-- @foreach ($list as $item)
                                @if ($item != '.ftpquota')
                                    <option value="{{$item}}">{{$item}}</option>
                                @endif
                            @endforeach --}}
                        </select>
                      </div>
                </div>
                <br>
                <div class="table-responsive" class="row" id="hidden">
                    <table id="dataTableExample" class="table" >
                        <thead>
                            <tr>
                                <th class="text-truncate" style="max-width: 10px;">Code Barres</th>
                                <th class="text-truncate" style="max-width: 10px;">Designation</th>
                                <th class="text-truncate" style="max-width: 10px;">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                
                 <div class="d-flex p-2 justify-content-center">
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
</div>

@endsection
@section('scripts')

<script>
//afficher le message de fichier ajouter avec success appré la redirection de puis la page upload
    $('#hidden').hide();
     $(".js-example-basic-single").select2();
       

        //charger la datatable si une valeur correct et coller ou une valeur ecrit aprés entrée
        $('.js-example-basic-single').on('select2:select', function (e) {
            $('#hidden').show();

            var pastedData = e.params.data.id;
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
                    // order: [[2, 'desc']],
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
                
                $.fn.dataTable.ext.errMode = 'none';

                    $('.table').on('error.dt', function(e, settings, techNote, message) {
                    Swal.fire({icon: 'error',title: 'La session est introuvable',text: 'la session est introuvable',showConfirmButton: false,timer: 1900});
                     })

                $('.dataTables_filter input').bind('keypress', function(e) {

                    if (e.which == 13 || e.originalEvent.clipboardData!= null) {
                        if(e.originalEvent.clipboardData!= null){
                            var pastedData = e.originalEvent.clipboardData.getData('text');
                        }else{
                            var pastedData = $('.dataTables_filter input').val();
                        }

	                    var table2 = $('.table').DataTable();
	                    var row = table2.row('#row-' + pastedData).data(); 
	                    var filteredData = table2.column( 0 ).data()
	                                        .filter( function ( value, index ) {
	                                            return value == pastedData ? true : false;
	                                        } );
	                    if(filteredData.length == 1){
	
	                    var quantite = row[5];
	                    var codeBar = row[19];
	                     var session = $('.js-example-basic-single').val();
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
                                        
	                                    $('div.dataTables_filter input', table2.table().container()).focus();
                                        oTable.api().ajax.reload();
                                        table2.search('').draw();
	                                }
	                            });
	
	                        } else {
	                            Swal.close()
	                        }
	
	                    });
	
	                    }else{
                             // to reload	
                           
	                        $('div.dataTables_filter input', table.table().container()).focus();


                            Swal.fire({
                            icon: 'error',
                            title: 'Code a barres introuvable',
                            text: 'Code a barres introuvable',
                            showConfirmButton: false,
                            timer: 1900
                            });
                            table2.search('').draw();
                        }
                        
                    }
                });
            }); 





//charger la datatable si une valeur correct et coller ou une valeur ecrit aprés entrée
    @if(!empty(Session::get('this')))
            
            $('.js-example-basic-single').val(["{{Session::get('this')}}","{{Session::get('this')}}"]).change();
            $('#hidden').show();
            var pastedData = "{{Session::get('this')}}";
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
                    // order: [[2, 'desc']],
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
                $.fn.dataTable.ext.errMode = 'none';

                $('.table').on('error.dt', function(e, settings, techNote, message) {
                    Swal.fire({icon: 'error',title: 'La session est introuvable',text: 'la session est introuvable',showConfirmButton: false,timer: 1900});
                })
                

                $('.dataTables_filter input').bind('keypress', function(e) {

                    if (e.which == 13 || e.originalEvent.clipboardData!= null) {
                        if(e.originalEvent.clipboardData!= null){
                            var pastedData = e.originalEvent.clipboardData.getData('text');
                        }else{
                            var pastedData = $('.dataTables_filter input').val();
                        }

	                    var table = $('.table').DataTable();
	                    var row = table.row('#row-' + pastedData).data(); 
	                    var filteredData = table.column( 0 ).data()
	                                        .filter( function ( value, index ) {
	                                            return value == pastedData ? true : false;
	                                        } );
	                    if(filteredData.length == 1){
	
	                    var quantite = row[5];
	                    var codeBar = row[19];
	                     var session = $('.js-example-basic-single').val();
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
                                        table.search('').draw();
	                                    var table3 = $('.table').dataTable();
	                                    // to reload	
                                        table.search('').draw();
                                        $('input[type=search]').val('');
	                                    $('div.dataTables_filter input', table.table().container()).focus();
                                        table3.api().ajax.reload();
	                                }
	                            });
	
	                        } else {
	                            Swal.close();
                                table.search('').draw();
	                        }
	
	                    });
	
	                    }else{
                            Swal.fire({
                            icon: 'error',
                            title: 'Code a barres introuvable',
                            text: 'Code a barres introuvable',
                            showConfirmButton: false,
                            timer: 1900
                            });
                          
	                            // to reload	
                                table.search('').draw();
	                            $('div.dataTables_filter input', table.table().container()).focus();
                                table.search('').draw();
                        }
                        
                    }
                });
    @endif



        
    // sauvgarder le array et exporter comme csv
    $('.button').click(function() {
        var session = $('.js-example-basic-single').val();
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
                        window.reload();
                    }
                });
            });

            
            
            
            //button pour balayer vers le bas

            $('#spnbottom').on("click", function () {
                var percentageToScroll = 100;
                var percentage = percentageToScroll / 100;
                var height = $(document).height() - $(window).height();
                var scrollAmount = height * percentage;
                jQuery("html, body").animate({
                    scrollTop: scrollAmount
                }, 900);
            });
          
            window.addEventListener("load", () => {
                function handleNetworkChange(event) {
                    if (navigator.onLine) {
                        Swal.fire({
                            icon: 'success',
                            title: 'vous êtes en ligne',
                            text: 'vous êtes en ligne',
                            showConfirmButton: false,
                            timer: 900
                            });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'vous êtes hors-ligne',
                            text: 'vous êtes hors-ligne',
                            showConfirmButton: false,
                            timer: 2000
                            });
                    }
                }
                window.addEventListener("online", handleNetworkChange);
                window.addEventListener("offline", handleNetworkChange);
            });

</script>
@endsection