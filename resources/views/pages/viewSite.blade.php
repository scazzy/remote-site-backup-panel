@extends('layouts.default')
@section('mainContent')

<h3>Add/Edit a new site</h3>
<p>
  This tool currently only supports servers with SSH access.
<br/>
Add or update a new website details. Take SSH and mysql Details</p>

<form method="post" class="pure-form pure-form-aligned">
  <legend><strong>Site SSH details</strong></legend>
    <fieldset>
        <div class="pure-control-group">
            <label>Site Name</label>
            <input name="site_name" type="text" required placeholder="eg: Hello World">
        </div>

        <div class="pure-control-group">
            <label>Site Address</label>
            <input name="ssh_address" type="text" required placeholder="IP or domain without protocol">
        </div>

        <div class="pure-control-group">
            <label>Username</label>
            <input name="ssh_username" type="text" required placeholder="Username">
        </div>

        <div class="pure-control-group">
            <label>Password</label>
            <input name="ssh_password" type="password" required placeholder="Password">
        </div>

        <div class="pure-control-group">
            <label>Home path</label>
            <input name="ssh_path" type="text" required placeholder="Website path from root">
        </div>
        <div class="pure-controls">
            <button type="button" id="btnTestConnectionSite" class="pure-button">Test connection</button>
        </div>
    </fieldset>

    <label for="db_yes" class="pure-checkbox">
        <input id="db_yes" name="db_yes" type="checkbox"> Add MySQL details
    </label>
    <!-- <legend><strong>MySQL Database details</strong></legend> -->
    <!-- <fieldset>
      <div class="pure-control-group">
          <label for="name">Site Name</label>
          <input id="name" type="text" placeholder="eg: Hello World">
      </div>
    </fieldset> -->

    <div class="pure-controls">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="pure-button pure-button-primary">Save Site</button>
    </div>

</form>

@endsection
