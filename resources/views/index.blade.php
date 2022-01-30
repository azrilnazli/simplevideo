<html lang="en">
<head>
  @include('partials.head')
</head>
<body>
  @include('partials.header')
  <div class="container-fluid">
      <div class="row">
          @include('partials.nav')
          <main class=" col-lg-6 px-md-4">
            <!-- content -->
            @include('contents.index')
          </main>
      </div>
  </div>
</body>
</html>