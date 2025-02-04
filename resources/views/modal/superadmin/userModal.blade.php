<div class="modal fade" id="users_modal" role="dialog" aria-labelledby="users_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add User</h4>
      </div>
      <div class="modal-body">
      	<form id="user_form" method="POST">
      		{{ csrf_field() }}
      		<input type="hidden" id="user_id" class="form-control" value="" name="user_id">
      		<div class="text-right">
            <button id="deactBtn" type="submit" class="btn btn-danger hide"><i class="fas fa-user-slash"></i> Deactivate User</button>
            <button id="actBtn" type="submit" class="btn btn-success hide"><i class="fas fa-user-check"></i> Activate User</button>
          </div>
      		<div class="form-group">
		        <label>First Name:</label>
		        <input type="text" class="form-control" value="" name="fname" required="">
		    </div>
		    <div class="form-group">
		        <label>Middle Name:</label>
		        <input type="text" class="form-control" value="" name="mname" required="">
		    </div>
		    <div class="form-group">
		        <label>Last Name:</label>
		        <input type="text" class="form-control" value="" name="lname" required="">
		    </div>
		    <div class="form-group">
		        <label>Contact Number:</label>
		        <input type="text" class="form-control" value="" name="contact" required="">
		    </div>
		    <div class="form-group">
		        <label>Email Address <small class="text-muted"><em>(Optional):</em></small></label>
		        <input type="text" class="form-control" name="email" value="">
		    </div>
		    <hr>
		    <div class="form-group">
		    	<label>Facility</label>
		    	<select class="select2" name="facility_id">
                    <option value="">Select Facility</option>
                    @foreach($facility as $row)
                        <option value="{{ $row->id }}">{{ $row->facilityname }}</option>
                    @endforeach
                </select>
		    </div>
		    <div class="form-group">
		        <label>Designation:</label>
		        <input type="text" class="form-control" value="" name="designation" required="">
		    </div>
		    <div class="form-group">
		        <label>Level:</label>
		        <select class="form-control" name="level" required="">
		            <option value="">Select options</option>
		            <option value="admin">Admin</option>
		        </select>
		    </div>
		    <hr>
		    <div class="form-group">
		        <label>Username</label>
		        <input type="text" class="form-control username_1" id="username" name="username" value="" required="">
		        <div class="username-has-error text-bold text-danger hide">
		            <small>Username already taken!</small>
		        </div>
            </div>
            <div class="form-group">
		        <label>Password</label>
		        <input type="password" pattern=".{8,}" title="Password - minimum of 8 characters" class="form-control" id="password1" name="password" required="">
		    </div>
		    <div class="form-group">
		        <label>Confirm Password</label>
		        <input type="password" pattern=".{8,}" title="Confirm password - minimum of 8 Characters" class="form-control" id="password2" name="confirm" required="">
		        <div class="password-has-error has-error text-bold text-danger hide">
		            <small>Password not match!</small>
		        </div>
		        <div class="password-has-match has-match text-bold text-success hide">
		            <small><i class="fa fa-check-circle"></i> Password matched!</small>
		        </div>
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