@extends('layouts.user_design')

@section('content')

<div class="container">
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd;">
        <table id="cart" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:50%">Product</th>
                    <th style="width:10%">Price</th>
                    <th style="width:8%">Quantity</th>
                    <th style="width:22%" class="text-center">Subtotal</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0 @endphp
                @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <tr data-id="{{ $id }}">
                            <td data-th="Product">
                                <div class="row">
                                    <div class="col-sm-3 hidden-xs">
                                        <img src="{{ asset('upload/product_images/' . $details['image']) }}" width="100" height="150" class="img-responsive"/>
                                    </div>
                                    <div class="col-sm-9">
                                        <h4 class="nomargin">{{ $details['name'] }}</h4>
                                    </div>
                                </div>
                            </td>
                            <td data-th="Price">P {{ $details['price'] }}</td>
                            <td data-th="Quantity">
                                <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" />
                            </td>
                            <td data-th="Subtotal" class="text-center">P {{ $details['price'] * $details['quantity'] }}</td>
                            <td class="actions text-center">
                                <button class="btn btn-danger btn-sm remove-from-cart" data-id="{{ $id }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Fixed Checkout Footer -->
    <div class="cart-footer text-right" style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-top: 1px solid #ddd;">
        <h3><strong>Totals: P{{ $total }}</strong></h3>
        <a href="{{ route('user.product') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Go Shopping</a>
        <button class="btn btn-success checkout-button"><i class="fa fa-shopping-cart"></i> Checkout</button>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">

    // Remove product from cart
    $(".remove-from-cart").click(function (e) {
    e.preventDefault();
    var ele = $(this);
    var productId = ele.data("id");

    if (confirm("Are you sure you want to remove this item?")) {
        $.ajax({
            url: '/cart/remove/' + productId,
            method: "DELETE",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert("Failed to remove item from cart.");
                }
            },
            error: function () {






                
                alert("Error while removing the item.");
            }
        });
    }
});

    // Checkout and clear cart
    $(".checkout-button").click(function (e) {
        e.preventDefault();

        if (confirm("Do you want to proceed to checkout?")) {
            $.ajax({
               url: '{{ route('user.checkout') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        alert("Checkout successful! Total price: P" + response.totalPrice);
                        window.location.reload();
                    } else {
                        alert("Something went wrong during checkout!");
                    }
                },
                error: function () {
                    alert("Checkout failed. Please try again.");
                }
            });
        }
    });

</script>
@endsection
