@if($getState())
    <a href="{{ url('/'. $getState()['slug']) }}" target="_blank">
        <img src="{{ asset('storage/' . $getState()['image']) }}" alt="Product Image" style="max-width: 100%; height: auto;">
    </a>
@else
    <p>No image available</p>
@endif
