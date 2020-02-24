<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-md-12">

            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard"></i> <?=$form_title?></h3>
              </div>
              <div class="card-body">
                <form method="post" action="#" id="initiate_form">
                  <?=$form?>

                  <!-- /.form group -->
                  <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit</button>
                  <button type="submit" class="btn btn-danger" onclick="window.history.go(-1); return false;"/><i class="fas fa-ban"></i> Cancel</button>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt"></i> Supporting Documents</h3>
              </div>
              <!-- Tab panes -->
              <div class="card-body">
                <form method="post" action="#" id="add_attachments">
                  <?=$form2?>
                  <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Add Document</button>
                </form>
                <p>
                  <table id="document_draft_list" class="table table-bordered table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10px">No</th>
                        <th>Name</th>
                        <th>File Size (KB)</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </p>
              </div>
            </div>

          </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<script>
$(document).ready(function() {

  $('#document_draft_list').dataTable({ 
    
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "<?=$base_url.$page?>/data/attachments_draft",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0,3 ], 
                "orderable": false, 
            },
            {
                "targets": [0,1,2],
                "className": "view_detail"
            }
        ],
        "drawCallback": function(settings, json) {
            $('[data-toggle="tooltip"]').tooltip();
            $('td.view_detail').on('click',function() {
                var url = $(this).parent().data("url");
                $(location).attr('href','<?=$base_url?>'+url);
            });
            $('.delete').on('click',function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '<?=$base_url.$page?>/data/delete_attachment',
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
                                    $('#document_draft_list').DataTable().ajax.reload();
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
            $(row).attr('data-id', data[4]);
            $(row).attr('data-url', data[5]);
            $(row).attr('style','cursor:pointer;');
        }

    });
  
});
</script>
  