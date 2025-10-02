@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Jadwal Tayang</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('staff.schedules.update', $schedule['id']) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Bioskop --}}
                    <div class="mb-3">
                        <label for="cinema_id" class="form-label">Bioskop</label>
                        <input type="text" id="cinema_id" value="{{ $schedule['cinema']['name'] }}" class="form-control"
                            disabled>
                    </div>

                    {{-- Film --}}
                    <div class="mb-3">
                        <label for="movie_id" class="form-label">Film</label>
                        <input type="text" id="movie_id" value="{{ $schedule['movie']['title'] }}" class="form-control"
                            disabled>
                    </div>

                    {{-- Harga --}}
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $schedule['price']) }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam Tayang --}}
                    <div class="mb-3">
                        <label class="form-label">Jam Tayang</label>
                        @foreach ($schedule['hours'] as $index => $hours)
                            <div class="d-flex align-items-center mb-2 hour-item">
                                <input type="time" name="hours[]" value="{{ $hours }}" class="form-control me-2">
                                @if ($index > 0)
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="this.closest('.hour-item').remove()">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach

                        <div id="additionalInput"></div>
                        <span class="text-primary my-3" style="cursor: pointer" onclick="addInput()">+Tambah Jam
                            tayang</span>
                        @if($errors->has('hours'))
                            <small class="text-danger d-block">{{ $errors->first('hours') }}</small>
                        @endif
                        @if($errors->has('hours.*'))
                            <small class="text-danger d-block">{{ $errors->first('hours.*') }}</small>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa-solid fa-paper-plane me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function addInput() {
            let content = `
                <div class="d-flex align-items-center mb-2 hour-additional">
                <input type="time" name="hours[]" class="form-control me-2">
                <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="this.closest('.hour-additional').remove()">
                <i class="fa-solid fa-circle-xmark"></i>
                </button>
                </div>`;
                document.querySelector("#additionalInput").innerHTML += content;
        }
    </script>
@endpush