<?php
    $user = Session::get('auth');
?>

@extends('layouts.app')

@section('content')
    <style>
        .ui-autocomplete
        {
            background-color: white;
            width: 20%;
            z-index: 1100;
            max-height: 300px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }
        .ui-menu-item {
            cursor: pointer;
        }

    </style>

    <div class="col-md-12">
        <div class="jim-content">
            <h3 class="page-header">Patient List</h3>
            <div class="row">
                   
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="{{ Session::get('keyword') }}" autofocus>
                            </div>
                 
                        <div class="col-md-4 float-right">
                            <button type="submit" class="btn btn-success btn-sm btn-flat">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="#patient_modal"   onclick="PatientBody('empty')" data-toggle="modal" class="btn btn-info btn-sm btn-flat add_info">
                                    <i class="fa fa-user-plus"></i> Add User
                                </a>
                        </div>
            </div>
                    <br>
            @if(count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-striped"  style="white-space:nowrap;">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age / DOB</th>
                            <th>Barangay</th>
                            <th style="width:18%;">Action</th>
                        </tr>
                        
                        <tr>
                            <td>
                             
                            </td>
                            <td>
                               
                            </td>
                            <td>
                               
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                        </tr>
                        
                        </tbody>
                    </table>
                </div>
                <ul class="pagination pagination-sm no-margin pull-right">
        
                </ul>
                @else
                <div class="alert alert-warning">
                <span class="text-warning">
                    <i class="fa fa-warning"></i> No Patient found!
                </span>
                </div>
           @endif
            <div class="clearfix"></div>
        </div>
    </div>
   
@include('modal.patient.patientmodal')
@endsection

<script>
    function PatientBody(patient_id){
        console.log(patient_id);
        var url = "<?php echo asset('doctor/patient/update'); ?>";
        var json = {
            "patient_id" : patient_id,
            "_token" : "<?php echo csrf_token(); ?>"
        };
        $.post(url,json,function(result){
            $(".patient_body").html(result);
        });
    }

</script>

