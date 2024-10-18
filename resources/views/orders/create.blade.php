@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Pesanan Baru</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.storeAdmin') }}" method="POST">
        @csrf

        <!-- User Input -->
        <div class="mb-4">
            <label for="customer_name" class="form-label">Nama Pelanggan</label>
            <input type="text" name="customer_name" class="form-control" placeholder="Masukkan nama pelanggan" value="{{ old('customer_name') }}" required>
        </div>

        <!-- Product Selection -->
        <div id="products-container">
            <div class="product-row mb-3 border rounded p-3">
                <h5 class="mb-3">Produk</h5>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <select name="products[]" class="form-select product-select" required>
                            <option value="" disabled selected>Pilih produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantities[]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add more products -->
        <button type="button" id="add-product" class="btn btn-secondary mb-3">Tambah Produk</button>

        <!-- Total Amount Calculation -->
        <div class="mb-4">
            <label for="total" class="form-label">Total Amount</label>
            <input type="hidden" name="total_amount" class="total-amount-value" value="0"> <!-- Hidden numeric value -->
            <input type="text" class="form-control total-amount" readonly value="Rp. 0.00"> <!-- Visible formatted value -->
        </div>

        <!-- Cash Input -->
        <div class="mb-4">
            <label for="cash_received" class="form-label">Nominal Pembayaran</label>
            <input type="number" name="cash_received" class="form-control cash-input" min="0" value="0" required>
        </div>

        <!-- Change Calculation -->
        <div class="mb-4">
            <label for="change" class="form-label">Kembalian</label>
            <input type="text" name="change" class="form-control change-amount" readonly value="Rp. 0.00">
        </div>

        <button type="submit" class="btn btn-success">Simpan Pesanan</button>
    </form>
</div>

@section('scripts')
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
        });
    </script>
@endif
<script>
    function calculateTotal() {
        const quantities = document.querySelectorAll('.quantity-input');
        const productSelects = document.querySelectorAll('.product-select');
        let totalAmount = 0;

        quantities.forEach((quantityInput, index) => {
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(productSelects[index].selectedOptions[0].dataset.price) || 0;
            totalAmount += quantity * price;
        });

        // Update both the displayed total and hidden value
        document.querySelector('.total-amount').value = "Rp. " + totalAmount.toFixed(2);
        document.querySelector('.total-amount-value').value = totalAmount.toFixed(2); // Store as a number
        calculateChange(totalAmount); // Update change calculation
    }

    function calculateChange(totalAmount) {
        const cashInput = document.querySelector('.cash-input');
        const changeInput = document.querySelector('.change-amount');
        const cashReceived = parseFloat(cashInput.value) || 0;
        const change = cashReceived - totalAmount;
        changeInput.value = "Rp. " + (change >= 0 ? change.toFixed(2) : 0).toString(); // Display change or 0 if negative
    }

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', calculateTotal);
    });

    document.getElementById('add-product').addEventListener('click', function () {
    var container = document.getElementById('products-container');
    var productHTML = `
        <div class="product-row mb-3 border rounded p-3 position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0" aria-label="Close"></button>
            <h5 class="mb-3">Produk</h5>
            <div class="row align-items-center">
                <div class="col-md-5">
                    <select name="products[]" class="form-select product-select" required>
                        <option value="" disabled selected>Pilih produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantities[]" class="form-control quantity-input" min="1" value="1" required>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', productHTML);

    // Add event listeners to new inputs
    container.lastChild.querySelector('.quantity-input').addEventListener('input', calculateTotal);
    container.lastChild.querySelector('.product-select').addEventListener('change', calculateTotal);
    
    // Add event listener for the close button
    container.lastChild.querySelector('.btn-close').addEventListener('click', function() {
        this.closest('.product-row').remove();
        calculateTotal(); // Recalculate total after removing the row
    });
    
    // Recalculate total
    calculateTotal();
});


    // Add event listener for cash input
    document.querySelector('.cash-input').addEventListener('input', function() {
        const totalAmount = parseFloat(document.querySelector('.total-amount-value').value) || 0; // Get numeric total
        calculateChange(totalAmount);
    });

    // Initial total calculation
    calculateTotal();
</script>
@endsection

@endsection
