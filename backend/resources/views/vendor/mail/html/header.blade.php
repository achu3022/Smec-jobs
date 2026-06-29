@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'SMECJobs' || true)
<img src="{{ env('FRONTEND_URL', 'https://smecjobs.com') }}/logo-new.webp" class="logo" alt="SMECJobs Logo" style="height: 40px; width: auto; max-width: 100%;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
