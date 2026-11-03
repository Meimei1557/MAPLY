@if($errors->any())
    <div class="form-errors" role="alert">
        <strong><i class="fa-solid fa-circle-exclamation"></i> Periksa kembali isian Anda:</strong>
        <ul>
            @foreach($errors->all() as $message)
                @if($loop->index < 6)<li>{{ $message }}</li>@endif
            @endforeach
        </ul>
    </div>
@endif
