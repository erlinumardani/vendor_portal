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
                    <p></p>
                  </div>
                  <div id="ap_ar" class="container tab-pane fade"><br>
                    <h3>Logs</h3>
                    <p></p>
                  </div>
                </div>
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

  
});

</script>
  