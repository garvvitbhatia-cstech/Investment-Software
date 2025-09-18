@extends('layout.admin.dashboard')

@section('content')

@php
$action = Route::getCurrentRoute()->getName();
$admin_type = Session::get('admin_type');

$PREV = Session::get('PREV');
$BRID = Session::get('BRID');
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row g-6">
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <a href="{{route('admin.todays-maturity-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-building-bank icon-md"></i> Today's Maturity</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getTodaysMaturity('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getTodaysMaturity('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <a href="{{route('admin.overdue-maturity-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-moneybag icon-md"></i> Maturiry Overdue</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getMaturityOverdue('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getMaturityOverdue('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <a href="{{route('admin.upcoming-maturity-overdue-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-coin-rupee icon-md"></i> Upcoming 7 Days Maturity</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getUpcoming7DayMaturity('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getUpcoming7DayMaturity('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>   
  </div>
</div>

@endsection 