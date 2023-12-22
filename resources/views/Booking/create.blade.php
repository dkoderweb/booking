@extends('welcome')

@section('content')
    <form method="POST" action="{{ isset($booking) ? route('booking.update', $booking->id) : route('booking.store') }}">
        @csrf
        @if(isset($booking))
            @method('PUT')
        @endif

        <div class="text-center">
            <h2>{{ isset($booking) ? 'Edit Booking' : 'Fill Booking Form' }}</h2>
        </div>
        <div class="row">
            <div class="mb-3 col-lg-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ isset($booking) ? $booking->name : old('name') }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 col-lg-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ isset($booking) ? $booking->email : old('email') }}">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 col-lg-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="full_day" name="type" id="full_day" {{ isset($booking) && $booking->type === 'full_day' ? 'checked' : '' }}>
                    <label class="form-check-label" for="full_day">
                        Full day
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="half_day" name="type" id="half_day" {{ isset($booking) && $booking->type === 'half_day' ? 'checked' : '' }}>
                    <label class="form-check-label" for="half_day">
                        Half day
                    </label>
                </div>
                @error('type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 col-lg-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="morning" name="slot" id="morning" {{ isset($booking) && $booking->slot === 'morning' ? 'checked' : '' }}>
                    <label class="form-check-label" for="morning">
                        Morning
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="evening" name="slot" id="evening" {{ isset($booking) && $booking->slot === 'evening' ? 'checked' : '' }}>
                    <label class="form-check-label" for="evening">
                        Evening
                    </label>
                </div>
                @error('slot')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 col-lg-6">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="date" value="{{ isset($booking) ? $booking->date : old('date') }}" min="{{ date('Y-m-d') }}">
                @error('date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 col-lg-6">
                <label for="time" class="form-label">Time</label>
                <input type="time" class="form-control" name="time" id="time" value="{{ isset($booking) ? $booking->time : old('time') }}">
                @error('time')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($booking) ? 'Update' : 'Submit' }}</button>
    </form>
@endsection

@section('script')
    <script>
        @if(session()->has('error'))
            $(document).ready(function(){
                toastr.error("{{ session()->get('error') }}")
            });
        @endif
    </script>
@endsection
