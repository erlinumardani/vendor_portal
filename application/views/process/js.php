<script>
$(document).ready(function() {

    var datalist = $('#datalist').dataTable({ 
 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "<?=$base_url.$page?>/data/list/"+<?=$node_id?>,
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0,5 ], 
                "orderable": false, 
            },
            {
                "targets": [0,1,2,3,4],
                "className": "view_detail"
            }
        ],
        "drawCallback": function(settings, json) {
            $('[data-toggle="tooltip"]').tooltip();
            $('td.view_detail').on('click',function() {
                var id = $(this).parent().data("id");
                $(location).attr('href','<?=$base_url.$page?>/data/view/'+id);
            });
            $('th').removeClass('view_detail');
            $('.delete').on('click',function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '<?=$base_url.$page?>/data/delete',
                            enctype: 'multipart/form-data',
                            data: {"id":$(this).data('id')},
                            type: 'POST',
                            dataType: 'json',
                        })
                        .done(function(data) {
                            if(data.status==true){
                                Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                                ).then(function(){
                                    $('#datalist').DataTable().ajax.reload();
                                });
                            }else{
                                Swal.fire(
                                'Failed!',
                                data.message,
                                'error'
                                );
                            }    
                        });
                        
                    }
                });
            });
            $('.update').on('click',function() {
                $.ajax({
                    url: '<?=$base_url.$page?>/data/pickup',
                    enctype: 'multipart/form-data',
                    data: {"id":$(this).data('id'),"node_id":<?=$node_id?>},
                    type: 'POST',
                    dataType: 'json',
                })
                .done(function(data) {
                    if(data.status==true){
                        Swal.fire(
                        'Picked Up!',
                        data.message,
                        'success'
                        ).then(function(){
                            $(location).attr('href','<?=$base_url.$page?>/data/update/'+data.id);
                        });
                    }else{
                        $(location).attr('href','<?=$base_url.$page?>/data/update/'+data.id);
                    }    
                });
            });

        },
        createdRow: function (row, data, index) {
            $(row).attr('data-id', data[6]);
            $(row).attr('style','cursor:pointer;');
        }

    });

    $('.menu').removeClass('active');
    $('#node_<?=$node_id?>').addClass('active');
    $('#node_<?=$node_id?>').parent().parent().parent('.has-treeview').addClass('menu-open');

} );

</script>