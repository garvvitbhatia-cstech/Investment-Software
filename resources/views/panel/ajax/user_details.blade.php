@if($userData->type == 'Manufacturer' || $userData->type == 'Modifier' || $userData->type == 'Distributer')
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Business / Brand Name:</label> {{$userData->company}}
</div>
</div>
@endif
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Name:</label> {{$userData->name}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Email:</label> {{$userData->email}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Mobile:</label> {{$userData->mobile}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Address:</label> {{$userData->address}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>City:</label> {{$userData->city}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>State:</label> {{$userData->state}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Country:</label> {{$userData->country}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Zipcode:</label> {{$userData->zipcode}}
</div>
</div>
@if($userData->type == 'Manufacturer' || $userData->type == 'Modifier' || $userData->type == 'Distributer')
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>GSTIN:</label> {{$userData->gstin}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Website or Social Link:</label> {{$userData->website}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Referral Code:</label> {{$userData->referral_code}}
</div>
</div>
@endif
@if($userData->type == 'Manufacturer')
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Type of Products Manufactured:</label> {{$userData->product_manufatured_type}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Manufacturing Unit Address:</label> {{$userData->manufacture_unit_address}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Want to Sell on CAABAA Store?:</label> {{$userData->sell_on_caabaa}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Avg. Monthly Output:</label> {{$userData->monthly_output}}
</div>
</div>
@endif
@if($userData->type == 'Modifier')
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Type of Services Offered:</label> {{$userData->service_type}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Workshop Address:</label> {{$userData->workshop_address}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Service Pincode Coverage:</label> {{$userData->service_pincode_coverage}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Years of Experience:</label> {{$userData->experience}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Want to Sell on CAABAA Store?:</label> {{$userData->service_on_caabaa}}
</div>
</div>

@endif

@if($userData->type == 'Distributer')

<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Workshop Address:</label> {{$userData->workshop_address}}
</div>
</div>
<div class="col-12 mb-4">
<div class="input-group-merge user_details">
<label>Service Pincode Coverage:</label> {{$userData->service_pincode_coverage}}
</div>
</div>


@endif