<?php
/**
 * Backup Dashboard For CTR
 * @author Elton Jain
 * @author:github http://github.com/scazzy
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>BackMeUp - Elton Jain</title>
    <link rel="stylesheet" href="/assets/css/app.css" type="text/css">
    <meta name="csrf-token" id="_token" content="{{ csrf_token() }}" />
  </head>
  <body>
    @yield('body')
    <script type="text/javascript" src="/assets/js/lib/zepto.min.js" defer></script>
    <script type="text/javascript" src="/assets/js/app.js" defer></script>
  </body>
</html>
