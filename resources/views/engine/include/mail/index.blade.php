<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
      {{ env('APP_NAME') }}
  </title>

</head>
<body>
  <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
    <div style="padding:20px 0">
        <div style="max-width:600px;margin:0 auto">
        <p style="font-size:1.2em;">Name: {{$data['name'] }}</p>
        <p style="font-size:1.2em;">Subject: {{$data['subject'] }}</p>
        <p style="font-size:1.2em;">Email: {{$data['email'] }}</p>
        <p style="font-size:1.2em;">Description: {{$data['description'] }}</p>

          <br />
          <p style="font-size:0.9em;">Regards, 
            <br />
            {{ env('APP_NAME') }}</p>
        </div>
    </div>
  </div>
</body>
</html>