@extends('layouts.default')
@section('mainContent')

<div class="align-center">
<p>
This is dashboard
</p>
<p>
  <a href="{{route('all_sites')}}" class="pure-button">View all sites</a>
  <a href="{{route('add_site')}}" class="pure-button">Add a site</a>
</p>
</div>


@endsection
