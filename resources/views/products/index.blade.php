@foreach ($cartItems as $item)
    <div>
        <h4>{{ $item->name }}</h4>
        <p>Price: {{ $item->price }}</p>
        <p>Quantity: {{ $item->quantity }}</p>
        <form action="{{ route('cart.remove') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $item->id }}">
            <button type="submit">Remove</button>
        </form>
    </div>
@endforeach

<form action="{{ route('cart.checkout') }}" method="POST">
    @csrf
    <button type="submit">Checkout</button>
</form>
