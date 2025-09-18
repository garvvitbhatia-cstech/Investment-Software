@if($records->count()>0)





    @foreach($records as $key => $row)


    


    @php


    	$read = "";


        if($row->read_status == 2){$read = "fw-bold";}


    @endphp


    


    <tr class="{{$read}}">


    <td>{{$key+1}}.</td>


    <td>{{$row->name}}</td>    


    <td>{{$row->email}}</td>    


    <td>{{$row->contact}}</td>    


    <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>


    <td>


    <div class="dropdown">


        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">


        <i class="icon-base ti tabler-dots-vertical"></i>


        </button>


        <div class="dropdown-menu">


        <a class="dropdown-item" style="cursor:pointer" onclick="getDetails('{{$row->id}}');" data-bs-toggle="modal" data-bs-target="#viewDetails"><i class="icon-base ti tabler-pencil me-1"></i> View</a>


        <a class="dropdown-item" onclick="deleteData('contacts','{{ $row->id }}');" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a>


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