@if($records->count()>0)

    @foreach($records as $key => $row)

    <tr>

    <td>{{$key+1}}</td>

    <td>{{$row->product_name}}</td>

    <td>{{$row->unit_price}}</td>

    <td>{{$row->min_unit}}</td>
    
    <td>{{$row->max_unit}}</td>

    <td>{{$row->per_unit_register_fees}}</td>

    <td>{{$row->return_in_days}}</td>

    <td>{{$row->interest_rate}}%</td>

    <td>

    <div class="dropdown">

    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

    <i class="icon-base ti tabler-dots-vertical"></i>

    </button>

    <div class="dropdown-menu">

    <a class="dropdown-item" style="cursor:pointer" href="{{url('panel/edit-product',base64_encode($row->id))}}"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>

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