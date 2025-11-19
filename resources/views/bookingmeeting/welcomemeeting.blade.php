@extends('layouts.bookingmeeting.appmeeting')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="border border-gray-200/60 rounded-xl shadow-lg p-6 bg-white/80 backdrop-blur-sm">
        <div id='calendar'></div>

    </div>
</div>
 <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>
@endsection