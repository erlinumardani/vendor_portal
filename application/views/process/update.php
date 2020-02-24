<!-- Main content -->
<section class="content">
  <form method="post" action="#" id="initiate_form">
    <div class="row">
    <div class="col-md-12">

            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard"></i> <?=$form_title?></h3>
              </div>
              <div class="card-body">

              <form method="post" action="#" id="initiate_form">

                <?=$form?>

                <button type="submit" class="btn btn-warning" onclick="window.history.go(-1); return false;"><i class="fas fa-backward"></i> Back</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Update</button>
                
              </form>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card card-primary card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#scanner">Attachments</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#ap_ar">Logs</a>
                  </li>
                </ul>
              </div>
              <!-- Tab panes -->
              <div class="card-body">
                <div class="tab-content">
                  <div id="scanner" class="container tab-pane active"><br>
                    <h3>Attachments</h3>
                    <p><table id="document_list" class="table table-bordered table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10px">No</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table></p>
                  </div>
                  <div id="ap_ar" class="container tab-pane fade"><br>
                    <h3>Logs</h3>
                    <p>
                      <table id="logs" class="table table-bordered table-hover datatable">
                      <thead>
                      <tr>
                        <th width="10px">No</th>
                        <th>Step</th>
                        <th>Updater</th>
                        <th>Action</th>
                        <th>Notes</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration (minutes)</th>
                      </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </p>
                  </div>
                </div>
              </div>
            </div>

          </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
  </form>
</section>
<!-- /.content -->
<script>

$(document).ready(function() {

  /* $('#process_action').on('change',function() {
      if(this.value=="Reject"){
        $('#notes').prop('disabled',true);
      }else{
        $('#notes').prop('disabled',false);
      }
  }); */

  $('#document_list').dataTable({ 
    
    "processing": true, 
    "serverSide": true, 
    "order": [], 
    "ajax": {
        "url": "<?=$base_url.$page?>/data/attachments_list/<?=$flow_request_id?>",
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
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '<?=$base_url.$page?>/data/delete_agent',
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
                                $('#agent_list').DataTable().ajax.reload();
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

  $('#logs').dataTable({ 
    
    "processing": true, 
    "serverSide": true, 
    "order": [], 
    "ajax": {
        "url": "<?=$base_url.$page?>/data/logs/<?=$flow_request_id?>",
        "type": "POST"
    },
    "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false, 
        }
    ]

  });
  
});

</script>
  