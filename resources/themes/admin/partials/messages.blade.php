<div class="message-container">
    @if ($messages->has('error'))
        @foreach ($messages->get('error') as $message)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!!$message!!}
            </div>
        @endforeach
    @endif

    @if ($messages->has('warning'))
        @foreach ($messages->get('warning') as $message)
            <div class="alert alert-dismissable alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!!$message!!}
            </div>
        @endforeach
    @endif

    @if ($messages->has('info'))
		@foreach ($messages->get('info') as $message)
			<div class="alert alert-dismissable alert-info">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				{!!$message!!}
			</div>
		@endforeach
	@endif

	@if ($messages->has('success'))
		@foreach ($messages->get('success') as $message)
			<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				{!!$message!!}
			</div>
		@endforeach
	@endif
</div>
