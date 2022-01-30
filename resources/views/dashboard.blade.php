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
            <!-- content -->
            <pre>@php include('../README.md' )@endphp</pre>
          </main>
      </div>
  </div>
</body>
</html>