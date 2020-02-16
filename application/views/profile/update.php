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

                <button type="submit" class="btn btn-warning" onclick="window.history.go(-1); return false;"><i class="fas fa-backward"></i> Back</button>
                <button type="submit" class="btn btn-primary" onclick="change_password_modal(); return false;"><i class="fas fa-lock"></i> Change Password</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Update</button>
                
              </form>
              
              

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="#" id="change_password">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><i class="fas fa-lock"></i> Change Password</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <?=$form2?>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- /.content -->
<script>
 function change_password_modal(params) {
    $('#modal-default').modal('toggle');
  }
  $('#change_password').validate({
      submitHandler: function () {
          $.ajax({
            url: '<?=$base_url.$page?>/data/change_password',
            enctype: 'multipart/form-data',
            data: {"id":$('#user_id').val(),"password":$('#password').val(),"old_password":$('#old_password').val()},
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
            if(data.status==true){
                Swal.fire(
                'Password Changed!',
                data.message,
                'success'
                ).then(function(){
                    $('#modal-default').modal('toggle');
                });
            }else{
                Swal.fire(
                'Failed!',
                data.message,
                'error'
                );
            }    
          });
        },
        rules: {
          "password":{
            "required":true
          },
          "confirm_password":{
            "required":true,
            "equalTo": "#password"
          },
          "old_password":{
            "required":true
          }
        },
        messages: {
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

</script>
  