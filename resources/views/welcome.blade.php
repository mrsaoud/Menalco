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

    table.dataTable td {
    font-size: 1em;
    font-weight: 700;
    }

    td.dt-control.sorting_1{
        padding: 0!important;
        width: 5px!important;
    }
    .dataTables_filter input{
        max-width: 85%!important;
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
                            <option value="">Sélectionner</option>
                            @foreach ($names as $item)
                                @if ($item != '.ftpquota' && $item != 'error_log' && $item != 'list.php' && $item != 'upload.php' )
                                    <option value="{{$item}}">{{$item}}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                </div>
                <br>
                <div class="table-responsive" class="row" id="hidden">
                    <table id="dataTableExample" class="table" >
                        <thead>
                            <tr>
                                <th style="max-width: 10px!important;"></th>
                                <th class="text-truncate" style="max-width: 10px;">Code Barre </th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="text-truncate" style="max-width: 10px;">Code Barre </th>
                            </tr>
                        </tfoot>
                    </table>
                
                 <div class="d-flex p-2 justify-content-center">
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 button">
                    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                    VALIDÉ
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
     function format(d) {
            // `d` is the original data object for the row
            return (
                '<table id="1"  cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                '<td>Designation:</td>' +
                '<td>' +
                d[8] +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Quantité:</td>' +
                '<td>' +
                d[5] +
                '</td>' +
                '</tr>' +
                '</table>'
            );
        }

        $('.dataTables_filter input').attr("placeholder", "Code a barre");
    // Array to track the ids of the details displayed rows
    var detailRows = [];

        //charger la datatable si une valeur correct et coller ou une valeur ecrit aprés entrée
        $('.js-example-basic-single').on('select2:select', function (e) {
            $('#hidden').show();
            $(".js-example-basic-single").prop("disabled", true);
            var pastedData = e.params.data.id;
             var name = pastedData.replace(/%20/g, " ");
             console.log(name);
            
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
                        "sSearch": "",
                    },
                    processing: true,
                    serverSide: false,
                    bPaginate: false,
                    responsive: true,
                    autoWidth: false,
                    lengthChange: false,
                    pageLength: 500,
                    order: [[0, 'desc']],
                    ajax: {
                        url: '/get-data',
                        type: 'GET',
                        data: function(d) {
                            d.codeBar = name
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                    },columns: [
                        {
                            "className":      'dt-control',
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": ''
                        },
                        {
                            data: 19,
                            name: 'Designation'
                        },
                        
                    ],
                    "initComplete": function(settings, json) {
                        $('div.dataTables_filter input', table.table().container()).focus();
                    }

                });

                var detailRows = [];
                
                $('.table tbody').on('click','td.dt-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    var idx = detailRows.indexOf(tr.attr('id'));

                    //console.log(row);
                    if (row.child.isShown()) {
                        // This row is already open - close it
                        row.child.hide();
                        // Remove from the 'open' array
                         detailRows.splice(idx, 1);
                        tr.removeClass('shown');
                    } else {
                        // Open this row
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                        
                        // Add to the 'open' array
                        if (idx === -1) {
                            detailRows.push(tr.attr('id'));
                        }
                    }
                });
            
                            // On each draw, loop over the `detailRows` array and show any child rows
                table.on('draw', function () {
                    detailRows.forEach(function(id, i) {
                        $('#' + id + ' td.details-control').trigger('click');
                    });
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
	                    var filteredData = table.column( 1 ).data()
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
	                            Swal.close()
                                table.search('').draw();
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
                            table.search('').draw();
                        }
                        
                    }
                });
              
               
            });


































// $(document).ready(function () { 
                     
// //charger la datatable si une valeur correct et coller ou une valeur ecrit aprés entrée
//     @if(!empty(Session::get('this')))
  
    
//             $('.js-example-basic-single').val(["{{Session::get('this')}}","{{Session::get('this')}}"]).change();
//             $('#hidden').show();
//             var pastedData = "{{Session::get('this')}}";
//                 var table = $('.table').DataTable({
//                     "createdRow": function(row, data, dataIndex) {
//                         if (data[5] != 0) {
//                             $(row).css("background-color", "green");
//                             $(row).css("color", "white");
//                         } else if (data[5] == 0) {
//                             $(row).css("background-color", "red");
//                             $(row).css("color", "white");
//                         }
//                     },
//                     destroy: true,
//                     oLanguage: {
//                         "sSearch": "Code à barres",
//                     },
//                     processing: true,
//                     serverSide: false,
//                     bPaginate: false,
//                     responsive: true,
//                     autoWidth: false,
//                     lengthChange: false,
//                     pageLength: 500,
//                     // order: [[2, 'desc']],
//                     ajax: {
//                         url: '/get-data',
//                         type: 'GET',
//                         data: function(d) {
//                             d.codeBar = pastedData
//                         },
//                         headers: {
//                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                         },

//                     },columns: [
//                         {
//                             "className":      'dt-control',
//                             "orderable":      false,
//                             "data":           null,
//                             "defaultContent": ''
//                         },
//                         {
//                             data: 19,
//                             name: 'Designation'
//                         },
                        
//                     ],
//                     "initComplete": function(settings, json) {
//                         $('div.dataTables_filter input', table.table().container()).focus();
//                     }
                    
//                 });

                   

                    
//                 $.fn.dataTable.ext.errMode = 'none';

//                 $('.table').on('error.dt', function(e, settings, techNote, message) {
//                     Swal.fire({icon: 'error',title: 'La session est introuvable',text: 'la session est introuvable',showConfirmButton: false,timer: 1900});
//                 })
                

//                 $('.dataTables_filter input').bind('keypress', function(e) {

//                     if (e.which == 13 || e.originalEvent.clipboardData!= null) {
//                         if(e.originalEvent.clipboardData!= null){
//                             var pastedData = e.originalEvent.clipboardData.getData('text');
//                         }else{
//                             var pastedData = $('.dataTables_filter input').val();
//                         }

// 	                    var table = $('.table').DataTable();
// 	                    var row = table.row('#row-' + pastedData).data(); 
// 	                    var filteredData = table.column(1).data()
// 	                                        .filter( function ( value, index ) {
// 	                                            return value == pastedData ? true : false;
// 	                                        } );
// 	                    if(filteredData.length == 1){
	
// 	                    var quantite =0;
// 	                    var codeBar = row[19];
// 	                     var session = $('.js-example-basic-single').val();
// 	                    var prices;
// 	                    Swal.fire({
// 	                        title: 'Combien d\'article?',
// 	                        icon: 'question',
// 	                        input: 'text',
// 	                        didOpen: () => {
// 	                            const inputRange = Swal.getInput()
// 	                            const inputNumber = Swal.getContent()
// 	                                .querySelector('#range-value')
// 	                            inputRange.addEventListener('input', () => {
// 	                                quantite = inputRange.value
// 	                            })
// 	                        }
// 	                    })
//                         .then((result) => {
// 	                        if (result.isConfirmed) {
// 	                            $.ajax({
// 	                                url: '/update-data?codeBar=' + codeBar + '&q=' + quantite + '&session=' + session,
// 	                                method: 'GET',
// 	                                headers: {
// 	                                    'X-CSRF-TOKEN': $(
// 	                                        'meta[name="csrf-token"]'
// 	                                    ).attr('content')
// 	                                },
	
// 	                                success: function() {
//                                         table.search('').draw();
// 	                                    var table3 = $('.table').dataTable();
// 	                                    // to reload	
//                                         table.search('').draw();
//                                         $('input[type=search]').val('');
// 	                                    $('div.dataTables_filter input', table.table().container()).focus();
//                                         table3.api().ajax.reload();
                                        
// 	                                }
// 	                            });
	
// 	                        } else {
// 	                            Swal.close();
//                                 table.search('').draw();
// 	                        }
	
// 	                    });
	
// 	                    }else{
//                             Swal.fire({
//                             icon: 'error',
//                             title: 'Code a barres introuvable',
//                             text: 'Code a barres introuvable',
//                             showConfirmButton: false,
//                             timer: 1900
//                             });
                          
// 	                            // to reload	
//                                 table.search('').draw();
// 	                            $('div.dataTables_filter input', table.table().container()).focus();
//                                 table.search('').draw();
//                         }
                        
//                     }
//                 });
//     @endif
//       // Add event listener for opening and closing details
//      $('.table tbody').on('click','td.dt-control', function () {
//                         var tr = $(this).closest('tr');
//                         var row = table.row(tr);
//                             // $('#' + rowData[19]).DataTable().destroy();
//                             // $('#' + rowData[19] + ' tbody').empty();
//                         if (row.child.isShown()) {
//                             // This row is already open - close it
//                             row.child.hide();
//                             tr.removeClass('shown');
//                         } else {
//                             // Open this row
//                             row.child(format(row.data())).show();
//                             tr.addClass('shown');
//                         }
//     });
// });






   

        
    // sauvgarder le array et exporter comme csv
    $('.button').click(function() {
        var session = $('.js-example-basic-single').val();

        Swal.fire({
        title: 'Souhaitez-vous enregistrer les modifications ?',
        showDenyButton: true,
        confirmButtonText: 'Sauvegarder',
        denyButtonText: `Annuler`,
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                    url: '/export-data?session='+ session,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $(
                            'meta[name="csrf-token"]'
                        ).attr('content')
                    }
                });
            Swal.fire('Les données sont validée!', '', 'success')
        } else if (result.isDenied) {
            Swal.fire('Les données ne sont pas validées', '', 'info')
        }
        })
       
                
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