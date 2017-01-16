@extends('layouts.default')
@section('mainContent')
<?php
$backupsList = $data['backups'];
$siteInfo = $data['site'];
$bcounter = 0;
?>
<a class="pure-button rfloat" href="{{route('edit_site', ['id' => $siteInfo['id']])}}">Edit Site</a>

<button class="pure-button btn-backup-site rfloat" data-site="{{$siteInfo['id']}}">Backup Now</button>
<h3>Site name: {{$siteInfo['site_name']}}</h3>
<p>Server: {{$siteInfo['ssh_address']}}</p>

<table class="pure-table pure-table-horizontal">
    <thead>
        <tr>
            <th>#</th>
            <th>Filename</th>
            <th>Checksum</th>
            <th>Created</th>
            <th></th>
        </tr>
    </thead>

    <tbody>

      @foreach($backupsList as $item)
        <tr>
            <td>{{++$bcounter}}</td>
            <td title="{{$item['filepath']}}">
                <input readonly value="{{$item['filename']}}"/>
            </td>
            <td title="{{'Checksum: '.$item['checksum']}}">{{\Str::limit($item['checksum'], 8)}}</td>
            <td>{{date('Y-m-d h:i', strtotime($item['created_at']))}}</td>
            <td class="align-right">
              <button class="pure-button btn-site-restore" data-backup="{{$item['id']}}">Restore this backup</button>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>

@if(!$backupsList)
<h3>No backups for this site yet.</h3>
@endif
@endsection
