<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::all();

        return view('Booking.index',compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Booking.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'type' => 'required|in:full_day,half_day',
            'slot' => 'required|in:morning,evening',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        if ($this->isFullDayBooked($request->input('date'))) {
            return redirect()->route('booking.create')->with('error', 'Full day is already booked for this date. Please choose a different date or select the half-day option.');
        }

        if ($this->isHalfDayBooked($request->input('date')) && $request->input('type') === 'full_day') {
            return redirect()->route('booking.create')->with('error', 'Half day is already booked for this date. Full day booking is not allowed. Please choose a different date or select the half-day option.');
        }

        if ($this->isSlotBooked($request->input('date'), $request->input('slot'), $request->input('type'))) {
            return redirect()->route('booking.create')->with('error', ucfirst($request->input('slot')) . ' is already booked for this date. Please choose a different slot or date.');
        }
            Booking::create($validatedData);

        return redirect()->route('booking.index')->with('success', 'Slot Book successfully');


    }
     
    private function isFullDayBooked($date)
    {
        return Booking::where('date', $date)->where('type', 'full_day')->exists();
    }

    private function isHalfDayBooked($date)
    {
        return Booking::where('date', $date)->where('type', 'half_day')->exists();
    }

    private function isSlotBooked($date, $slot, $type)
    {
        if ($type === 'full_day') {
            return Booking::where('date', $date)->where('type', 'full_day')->exists();
        }    
        return Booking::where('date', $date)->where('slot', $slot)->exists();
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        return view('Booking.create', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'type' => 'required|in:full_day,half_day',
            'slot' => 'required|in:morning,evening',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $isSameBooking = $booking->date == $request->input('date')
        && $booking->type == $request->input('type')
        && $booking->slot == $request->input('slot');

        if (!$isSameBooking) {
            if ($this->isFullDayBooked($request->input('date'), $booking->id)) {
                return redirect()->route('booking.edit', $booking->id)->with('error', 'Full day is already booked for this date. Please choose a different date or select the half-day option.');
            }

            if ($this->isHalfDayBooked($request->input('date'), $booking->id) && $request->input('type') === 'full_day') {
                return redirect()->route('booking.edit', $booking->id)->with('error', 'Half day is already booked for this date. Full day booking is not allowed. Please choose a different date or select the half-day option.');
            }

            if ($this->isSlotBooked($request->input('date'), $request->input('slot'), $request->input('type'), $booking->id)) {
                return redirect()->route('booking.edit', $booking->id)->with('error', ucfirst($request->input('slot')) . ' is already booked for this date. Please choose a different slot or date.');
            }
        }

        $booking->update($validatedData);


        return redirect()->route('booking.index')->with('success', 'Booking updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
    
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully');
    }
}
