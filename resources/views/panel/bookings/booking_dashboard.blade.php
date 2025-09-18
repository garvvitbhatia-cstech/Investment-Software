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
        <a href="{{route('admin.cash-collection-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-building-bank icon-md"></i> Today's Cash Collection</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getTodaysCashCollection('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getTodaysCashCollection('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <a href="{{route('admin.bank-collection-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-moneybag icon-md"></i> Today's Bank Collection</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getTodaysBankCollection('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getTodaysBankCollection('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <a href="{{route('admin.cleared-collection-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-coin-rupee icon-md"></i> Today's Cleared Amount</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getTodaysClearedCollection('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getTodaysClearedCollection('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <a href="{{route('admin.bounced-collection-report')}}"><div class="card-header pb-3">
          <h5 class="mb-3 card-title"><i class="icon-base ti tabler-coin-rupee icon-md"></i> Today's Bounced Amount</h5>
          <h4 class="mb-0">
          	@if($PREV == 1 || $PREV == 3)
          		{{getTodaysBouncedCollection('mel_investment')}}
            @endif
            @if($PREV == 2)
          		{{getTodaysBouncedCollection('mel_investment',$BRID)}}
            @endif
          </h4>
        </div></a>
      </div>
    </div>   
  </div>
</div>

@endsection 