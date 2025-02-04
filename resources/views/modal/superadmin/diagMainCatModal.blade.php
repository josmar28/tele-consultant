<div class="modal fade" id="main_cat_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Diagnosis</h4>
      </div>
      <div class="modal-body">
      	<form id="main_cat_form" method="POST">
      		{{ csrf_field() }}
          <div class="text-right">
            <button id="deleteBtn" type="submit" class="btnSave btn btn-danger hide"><i class="fas fa-trash"></i> Delete</button>
          </div>
          <input type="hidden" class="form-control" value="" autofocus="" name="main_id" id="main_id">
      		<div class="form-group">
              <label>Daignosis Category Code:</label>
              <input type="text" class="form-control" value="" autofocus="" name="diagcat" required="">
          </div>
          <div class="form-group">
              <label>Diagnosis Category Description:</label>
              <input type="text" class="form-control" value="" name="catdesc">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
        <button type="submit" class="btnSave btn btn-success"><i class="fas fa-check"></i> Save</button>
  	</form>
      </div>
    </div>
  </div>
</div>