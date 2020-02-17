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
                "targets": [ 0,4 ], 
                "orderable": false, 
            },
            {
                "targets": [0,1,2,3],
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

        },
        createdRow: function (row, data, index) {
            $(row).attr('data-id', data[5]);
            $(row).attr('style','cursor:pointer;');
        }

    });

    $('.menu').removeClass('active');
    $('#node_<?=$this->uri->segment(4)?>').addClass('active');
    $('#node_<?=$this->uri->segment(4)?>').parent().parent().parent('.has-treeview').addClass('menu-open');

} );

</script>