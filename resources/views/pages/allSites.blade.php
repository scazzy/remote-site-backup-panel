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
            <tr>
                <td>1</td>
                <td>Honda</td>
                <td>Accord</td>
                <td>Accord</td>
                <td>2009</td>
            </tr>

            <tr>
                <td>2</td>
                <td>Toyota</td>
                <td>Camry</td>
                <td>Camry</td>
                <td>2012</td>
            </tr>

            <tr>
                <td>3</td>
                <td>Hyundai</td>
                <td>Elantra</td>
                <td>2010</td>
                <td>2010</td>
            </tr>
        </tbody>
    </table>

@endsection
