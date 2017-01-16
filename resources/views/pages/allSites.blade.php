@extends('layouts.default')
@section('mainContent')
<h3>All your sites</h3>
<p>List of all sites, option to modify them</p>

<table class="pure-table pure-table-horizontal">
    <thead>
        <tr>
            <th>#</th>
            <th>Site Name</th>
            <th>Address</th>
            <th>Last Backup</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
      @foreach($data['sites'] as $item)
        <tr>
            <td>{{$item['id']}}</td>
            <td><a href="{{route('site_backups', ['id'=>$item['id']])}}">{{$item['site_name']}}</a></td>
            <td>{{$item['ssh_address']}}</td>
            <td>{{$item['last_backup'] ? date('Y-m-d h:i', strtotime($item['last_backup'])) : ''}}</td>
            <td class="align-right">
              <a href="{{route('site_backups', ['id'=>$item['id']])}}"><small>View backups</small></a>
              &nbsp;
              <button class="pure-button btn-backup-site pure-button-primary" data-site="{{$item['id']}}">Backup</button>
              <a class="pure-button pure-button-success" href="{{route('edit_site', ['id' => $item['id']])}}">Edit</a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>

@if(!$data['sites'])
<h3>No sites added for backup yet.</h3>
<p>To add a new site <a href="{{route('add_site')}}">click here</a></p>
@endif
@endsection
