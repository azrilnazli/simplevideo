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
              <h1 class="h2">Dashboard</h1>
            </div>
            
            <!-- content -->
            <pre>@php include('../README.md' )@endphp</pre>
          </main>
      </div>
  </div>
</body>
</html>