@extends('layout.admin.dashboard')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card mb-2 p-3">

    <form id="searchForm" name="searchForm">

      <div class="row">

        <div class="col-md-2">

          <input type="text" class="form-control" name="search_keywords" placeholder="Search by keywords" id="defaultFormControlInput" />
          
        </div>
        
        <div class="col-md-2">
        
        	<select name="search_status" id="search_status" class="form-select">
            	
                <option value="">Select Status</option>
                
                <option value="1">Active</option>
                
                <option value="2">Inactive</option>
            
            </select>

        </div>

        <div class="col-md-1"> <a style="color:#FFF" id="searchbuttons" onclick="filterData('search');" class="btn btn-primary waves-effect waves-light">Search</a> </div>

        <div class="col-md-1"> <a style="color:#FFF" onclick="resetFilterForm();" class="btn btn-danger waves-effect waves-light">Reset</a> </div>

      </div>

    </form>

  </div>

  <!-- Basic Bootstrap Table -->

  <div class="card">

    <h5 class="card-header">Branches <a href="{{route('admin.add-branch')}}" style="float:right; color:#FFF" class="btn btn-success waves-effect waves-light">Add</a></h5>

    <div class="table-responsive text-nowrap">

      <table class="table">

        <thead>

          <tr>

            <th>#</th>

            <th>Branch ID</th>

            <th>Branch Name</th>

            <th>Branch Code</th>

            <th>Status</th>
 

            <th>Actions</th>

          </tr>

        </thead>

        <tbody class="table-border-bottom-0" id="replaceHtml">

          <tr>

            <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>

          </tr>

        </tbody>

      </table>

    </div>

  </div>

  <!--/ Basic Bootstrap Table --> 

</div>

<script type="text/javascript">

    $(document).ready(function(){

        filterData('simple');

    });        

    function filterData(type = null){

        if(type =='search'){$('#searchbuttons').html('Searching..');}

        $.ajax({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

            type: 'POST',

            data: $('#searchForm').serialize(),

            url: "{{ url('/panel/branches_paginate') }}",

            success: function(response){

                $('#replaceHtml').html(response);

                $('#searchbuttons').html('Search');

            }

        });

    }

</script> 

@endsection