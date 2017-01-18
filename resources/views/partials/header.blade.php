<header class="header pure-g">
  <h1 class="pure-u-1-5 logo">
    <a href="/">BackMeUp</a>
  </h1>

  <div class="pure-u-4-5 align-right">
    <nav class="pure-menu pure-menu-horizontal">
          <ul class="pure-menu-list align-left">
            <li class="pure-menu-item"><a href="/" class="pure-menu-link">Home</a></li>
            <li class="pure-menu-item"><a href="{{route('all_sites')}}" class="pure-menu-link">All sites</a></li>
            <li class="pure-menu-item"><a href="{{route('add_site')}}" class="pure-menu-link">Add a site</a></li>
            <li class="pure-menu-item"><a href="{{route('jobs')}}" class="pure-menu-link">Scheduler</a></li>
{{-- 
            <li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover">
                <a href="{{route('all_sites')}}" class="pure-menu-link">Sites</a>
                <ul class="pure-menu-children">
                    <li class="pure-menu-item"><a href="{{route('all_sites')}}" class="pure-menu-link">All sites</a></li>
                    <li class="pure-menu-item"><a href="{{route('add_site')}}" class="pure-menu-link">Add a site</a></li>
                </ul>
            </li> --}}
            {{-- <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link">Backup</a></li>
            <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link">Restore</a></li> --}}
            <!-- Scheduler -->
        </ul>
    </nav>
  </div>
</header>
