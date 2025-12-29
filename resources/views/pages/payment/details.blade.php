<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="payment_details"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Payment Details"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('payment.details') }}">
                        <select id="payment_gateway" name="gateway" class="form-control" onchange="this.form.submit();">
                            <option value="" disabled selected>Select a gateway</option>
                            <option value="vodafone" {{ request('gateway') == 'vodafone' ? 'selected' : '' }}>Vodafone</option>
                            <option value="instaPay" {{ request('gateway') == 'instaPay' ? 'selected' : '' }}>InstaPay</option>
                        </select>
                    </form>
                </div>
            </div>

            @if(isset($details))
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>{{ ucfirst($gateway) }} Payment Details</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('payment.update', ['id' => $details->id, 'gateway' => $gateway]) }}">
                            @csrf
                            @method('PUT')

                            @if($gateway == 'vodafone')
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                                           value="{{ old('phone_number', $details->phone_number) }}" required>
                                </div>
                            @elseif($gateway == 'instaPay')
                                <div class="mb-3">
                                    <label for="receiver_name" class="form-label">Receiver Name</label>
                                    <input type="text" id="receiver_name" name="receiver_name" class="form-control"
                                           value="{{ old('receiver_name', $details->receiver_name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="account_number" class="form-label">Account Number</label>
                                    <input type="text" id="account_number" name="account_number" class="form-control"
                                           value="{{ old('account_number', $details->account_number) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control"
                                           value="{{ old('bank_name', $details->bank_name) }}" required>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">Update Details</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </main>
</x-layout>
