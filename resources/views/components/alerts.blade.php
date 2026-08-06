<style>
    .alert {
        font-family: 'Poppins', sans-serif;
        border-radius: 14px;
        border: 1.5px solid transparent;
        padding: 14px 16px;
        font-size: 0.88rem;
        max-width: 720px;
        margin: 0 auto 16px;
    }

    .alert-success {
        background: rgba(58, 200, 130, 0.1);
        border-color: rgba(58, 200, 130, 0.35);
        color: #6fe3ad;
    }

    .alert-warning {
        background: rgba(255, 190, 60, 0.1);
        border-color: rgba(255, 190, 60, 0.35);
        color: #ffcc66;
    }

    .alert-danger {
        background: rgba(255, 61, 90, 0.1);
        border-color: rgba(255, 61, 90, 0.35);
        color: #ff8095;
    }

    .alert ul { padding-left: 18px; }
    .alert .btn-close { filter: invert(1) brightness(1.8); }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please check the details.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif