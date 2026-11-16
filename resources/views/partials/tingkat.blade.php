@if(!empty($tingkat))
    <span class="lencana {{ strtolower($tingkat) }}">{{ $tingkat }}</span>
@else
    <span class="lencana aman">Aman</span>
@endif
