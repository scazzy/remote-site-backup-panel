@extends('layouts.default')
@section('mainContent')

<h3>Add/Edit a new site</h3>
<p>
  This tool currently only supports servers with SSH access.
<br/>
Add or update a new website details. Take SSH and mysql Details</p>

<form class="pure-form pure-form-aligned">
  <legend><strong>Site SSH details</strong></legend>
    <fieldset>
        <div class="pure-control-group">
            <label for="name">Site Name</label>
            <input id="name" type="text" required placeholder="eg: Hello World">
        </div>

        <div class="pure-control-group">
            <label for="password">Site Address</label>
            <input id="text" type="text" required placeholder="IP or domain without protocol">
        </div>

        <div class="pure-control-group">
            <label for="email">Username</label>
            <input id="email" type="email" required placeholder="Username">
        </div>

        <div class="pure-control-group">
            <label for="foo">Password</label>
            <input id="foo" type="password" required placeholder="Password">
        </div>

        <div class="pure-control-group">
            <label for="foo">Home path</label>
            <input id="foo" type="text" required placeholder="Website path from root">
        </div>
        <div class="pure-controls">
            <button type="button" class="pure-button">Test connection</button>
        </div>
    </fieldset>

    <label for="cb" class="pure-checkbox">
        <input id="cb" type="checkbox"> Add MySQL details
    </label>
    <!-- <legend><strong>MySQL Database details</strong></legend> -->
    <!-- <fieldset>
      <div class="pure-control-group">
          <label for="name">Site Name</label>
          <input id="name" type="text" placeholder="eg: Hello World">
      </div>
    </fieldset> -->

    <div class="pure-controls">
        <button type="submit" class="pure-button pure-button-primary">Save Site</button>
    </div>

</form>

@endsection
