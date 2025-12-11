@extends('layouts.app')

@section('content')
<h1>Your Cart</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(empty($cart))
    <p>Cart is empty. <a href="{{ route('home') }}">Shop now</a></p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.remove', $id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove?')">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Total: Rp {{ number_format($total, 0, ',', '.') }}</h3>
    <a href="{{ route('checkout') }}" class="btn btn-success">Checkout</a>
@endif
@endsection