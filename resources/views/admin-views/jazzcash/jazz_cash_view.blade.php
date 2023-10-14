<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Processing</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
     .spinner-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    .spinner {
      font-size: 5rem; /* Adjust the size as desired */
    }
  </style>
    <script type="text/javascript">
        function closethisasap() {
            document.forms['redirectpost'].submit();
        }
    </script>
</head>
<body onload="closethisasap();">
      <div class="spinner-container">
    <div class="spinner">
      <i class="fas fa-spinner fa-spin"></i>
    </div>
  </div>

<form name="redirectpost" action="{{ Config::get('constant.jazzcash.TRANSACTION_POST_URL') }}" method="POST">

    <?php
    $post_data =Session::get('post_data');
     //echo "<pre>";
     //print_r($post_data);
    
?>
@foreach ($post_data  as $key => $value )
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
@endforeach
 
</form>
</body>
</html>



