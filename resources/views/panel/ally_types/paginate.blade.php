@if($records->count()>0)

    @foreach($records as $key => $row)

    <tr>

    <td>{{$key+1}}</td>

    <td>{{$row->ally_type}}</td>

    <td>{{$row->ally_code}}</td>

    <td>{{$row->ally_fees}}</td>

    <td>

    <div class="dropdown">

    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

    <i class="icon-base ti tabler-dots-vertical"></i>

    </button>

    <div class="dropdown-menu">

    <a class="dropdown-item" style="cursor:pointer" href="{{url('panel/edit-ally-type',base64_encode($row->id))}}"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>

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