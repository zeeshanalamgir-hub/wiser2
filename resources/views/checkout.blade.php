<html>
  <head>
    <title>Checkout</title>
  </head>
  <body>
    <form action="{{ route('make.payment') }}" method="POST">
        @csrf
      <button type="submit">Checkout</button>
    </form>
  </body>
</html>
