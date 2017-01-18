@extends('layouts.default')
@section('mainContent')

<h3>{{$data ? 'Edit your' : 'Add a new'}} site</h3>
<p>
  This tool currently only supports servers with SSH access.
<br/>
Add or update a new website details.
</p>

@if(Request::input('saved'))
<div class="alert alert-success">
  <p>Saved successfully</p>
</div>
@endif
<form method="post" class="pure-form pure-form-aligned">
  <legend><strong>Site SSH details</strong></legend>
    <fieldset>
        <!-- <input name="id" type="text" required placeholder="eg: Hello World" value="{{$data['site_name']}}"> -->

        <div class="pure-control-group">
            <label>Site Name</label>
            <input name="site_name" type="text" required placeholder="eg: Hello World" value="{{$data['site_name']}}">
        </div>

        <div class="pure-control-group">
            <label>Site Address</label>
            <input name="ssh_address" type="text" required placeholder="IP or domain without protocol" value="{{$data['ssh_address']}}">
        </div>

        <div class="pure-control-group">
            <label>Username</label>
            <input name="ssh_username" type="text" required placeholder="Username" value="{{$data['ssh_username']}}">
        </div>

        <div class="pure-control-group">
            <label>Password</label>
            <input name="ssh_password" type="password" required placeholder="Password" value="{{base64_decode($data['ssh_password'])}}">
        </div>

        <div class="pure-control-group">
            <label>Home path</label>
            <input name="ssh_path" type="text" required placeholder="Website path from root" value="{{$data['ssh_path']}}">
        </div>
        <div class="pure-controls">
            <button type="button" id="btnTestConnectionSite" class="pure-button">Test connection</button>
        </div>
    </fieldset>

    <label for="btnSiteMysqlToggle" class="pure-checkbox">
        <input id="btnSiteMysqlToggle" name="db_yes" type="checkbox"{{$data['is_db_backup_enabled']?' checked':''}}> Add MySQL details
    </label>
    <div id="form-section-mysql" class="{{$data['is_db_backup_enabled']?'':'hidden'}}">
        <legend><strong>MySQL Database details</strong><br/>
      <small>Note: You can only backup, but not restore mysql database as of this app version</small>

        </legend>

        <fieldset>
          <div class="pure-control-group">
              <label>Host</label>
              <input name="db_host" type="text" placeholder="localhost" value="{{$data['db_host']}}"/>
          </div>
          <div class="pure-control-group">
              <label>Database</label>
              <input name="db_database" type="text" placeholder="Database" value="{{$data['db_database']}}"/>
          </div>
          <div class="pure-control-group">
              <label>Username</label>
              <input name="db_username" type="text" placeholder="Username" value="{{$data['db_username']}}"/>
          </div>
          <div class="pure-control-group">
              <label>Password</label>
              <input name="db_password" type="password" placeholder="Password" value="{{base64_decode($data['db_password'])}}"/>
          </div>
        </fieldset>
    </div>

    <br/>
    <br/>
    <div class="pure-control-group">
        <label>Notes</label>
        <textarea name="notes" placeholder="Notes">{{$data['notes']}}</textarea>
    </div>
    <div class="pure-controls">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <a href="{{route('all_sites')}}" class="pure-button">Cancel</a>
        <button type="submit" class="pure-button pure-button-primary">Save Site</button>
    </div>

</form>

@endsection
