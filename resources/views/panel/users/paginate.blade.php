@if($records->count()>0)

    @foreach($records as $key => $row)

    <tr>

    <td>{{$key+1}}</td>

    <td>{{$row->username}}</td>

    <td>{{$row->mobile}}</td>

    

    <td>

    @php

    if($row->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}

    @endphp

    <a style="cursor:pointer" onclick="changeStatus('users','{!!$row->id!!}');" id="status_{{$row->id}}" class="badge {{$class}} me-1">{{$label}}</a>

    <input type="hidden" id="status_value_{{$row->id}}" value="{!!$row->status!!}" />

    </td>

    

    <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>

    <td>

    <div class="dropdown">

    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

    <i class="icon-base ti tabler-dots-vertical"></i>

    </button>

    <div class="dropdown-menu">

    <a class="dropdown-item" style="cursor:pointer" href="{{url('panel/edit-admin',base64_encode($row->id))}}"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>

    @if(Session::get('admin_type') == 'Admin')

    <?php /*?><a class="dropdown-item" onclick="deleteData('users','{{ $row->id }}');" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a><?php */?>

    @endif

    </div>

    </div>

    </td>

    </tr>



    @endforeach

    @else

    <tr>



        <td align="center" colspan="10">Record not found</td>



    </tr>



    @endif



    <tr>



        <td align="center" colspan="10">



            <div id="pagination">{{ $records->appends(request()->except('page'))->links('pagination.front') }}</div>



        </td>



    </tr>