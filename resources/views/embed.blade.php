<html lang="en">
<head>
  @include('partials.head')
</head>
<body>
  @include('partials.header')
  <div class="container-fluid">
      <div class="row">
          @include('partials.nav')
          <main class=" col-lg-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h1 class="h2">Embed Code</h1>
            </div>
            
            <!-- content -->
            <pre class="bg-dark p-5  text-light">{{$html}}</pre>
            <p>Change <span class="lead">/foldername/manifest.m3u8</span> URI relative to your server root path.
          </main>
      </div>
  </div>
</body>
</html>