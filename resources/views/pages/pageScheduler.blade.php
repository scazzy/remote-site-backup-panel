@extends('layouts.default')
@section('mainContent')
<h3>Backup Scheduler</h3>
<p>Schedule your backups, quick and easy</p>
<?php
$scounter = 0;
?>
<form method="post" class="pure-form" id="formAddSchedule">
    <fieldset>
        <legend><strong>Add a schedule</strong></legend>
        <select name="site_id" required style="width: 150px;">
            <option value="">Select</option>
            @foreach($data['sites'] as $item)
            <option value="{{$item['id']}}">{{$item['site_name']}}</option>
            @endforeach
        </select>
        <input type="text" name="cron_schedule" required placeholder="Schedule. Eg: * * * * * *">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="pure-button pure-button-primary">Add Schedule</button>
    </fieldset>
</form>

<hr/>

<table class="pure-table pure-table-horizontal">
    <thead>
        <tr>
            <th>#</th>
            <th>Site</th>
            <th>Schedule</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['schedules'] as $item)
        <tr>
            <td>{{++$scounter}}</td>
            <td>{{$item->site_name}}</td>
            <td>{{$item->cron_schedule}}</td>
            <th></th>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection