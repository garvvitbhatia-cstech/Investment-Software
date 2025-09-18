<div class="col-12 mb-4">
    <div class="input-group-merge user_details">
    	<label>Name:</label> {{$enquiry->name}}
    </div>
</div>
<div class="col-12 mb-4">
    <div class="input-group-merge user_details">
    	<label>Email:</label> {{$enquiry->email}}
    </div>
</div>
<div class="col-12 mb-4">
    <div class="input-group-merge user_details">
    	<label>Contact:</label> {{$enquiry->contact}}
    </div>
</div>
<div class="col-12 mb-4">
    <div class="input-group-merge user_details">
    	<label>Subject:</label> {{$enquiry->subject}}
    </div>
</div>
<div class="col-12 mb-4">
    <div class="input-group-merge user_details">
    	<label>Message:</label> {!! nl2br($enquiry->message) !!}
    </div>
</div>