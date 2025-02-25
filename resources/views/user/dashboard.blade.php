@extends('layouts.user_design')

@section('content')
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
                                <img src="{{ asset('storage/' . $details['image']) }}" width="100" height="150" class="img-responsive"/>
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
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right"><h3><strong>Total P{{ $total }}</strong></h3></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                <a href="{{url('user/products')}}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Go Shopping</a>
              
               

                
            </td>
        </tr>
        
    </tfoot>
</table>
@endsection

@section('scripts')
<script type="text/javascript">


    // Remove product from cart
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);

        if (confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{ route('remove.from.cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.parents("tr").attr("data-id")
                },
                success: function () {
                    window.location.reload();
                }
            });
        }
    });

    // Checkout and clear cart
    $(".checkout-button").click(function (e) {
        e.preventDefault();

        if (confirm("Do you want to proceed to checkout?")) {
            $.ajax({
                url: '{{ route('checkout') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        alert("Checkout successful! Total price: $" + response.totalPrice);
                        $("#cart tbody").empty();
                        $("#cart tfoot").html(`
                            <tr>
                                <td colspan="5" class="text-center">
                                    <h3>Your cart is empty.</h3>
                                </td>
                            </tr>
                        `);
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



