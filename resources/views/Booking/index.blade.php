@extends('welcome')
@section('content')
    <div class="text-end">
        <a href="{{ route('booking.create') }}">
            <button type="button" class="btn btn-primary">Add</button>
        </a>
    </div>
    <div class="container mt-4">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Slot</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->name }}</td>
                        <td>{{ $book->email }}</td>
                        <td>{{ $book->type }}</td>
                        <td>{{ $book->slot }}</td>
                        <td>{{ $book->date }}</td>
                        <td>{{ $book->time }}</td>
                        <td class="d-flex">
                            <a href="{{ route('booking.edit', ['booking' => $book->id]) }}" class="mx-2">
                                <button type="button" class="btn btn-warning">Update</button>
                            </a>

                            <form method="POST" action="{{ route('booking.destroy', $book->id) }}" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script>
        @if (session()->has('success'))
            $(document).ready(function() {
                toastr.success("{{ session()->get('success') }}")
            });
        @endif
        $(document).ready(function() {
            $('#example').DataTable();
        });

        function confirmDelete() {
            return confirm("Are you sure you want to delete this booking?");
        }
    </script>
@endsection
