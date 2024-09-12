@if($getState())
    <img src="{{ asset('storage/' . $getState()) }}" alt="Product Image" style="max-width: 100%; height: auto;">
@else
    <p>No image available</p>
@endif
