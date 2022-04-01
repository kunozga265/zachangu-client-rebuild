<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Employer Alert!</title>
</head>
<body>
<p>Hi Admin!</p>
<p>User {{$employeeName}} has submitted details for their employer. Below are the details;</p>
<div>
    <p>Workplace details</p>
    <ul>
        <li>Name: {{$employer->name}}</li>
        <li>Email: {{$employer->email}}</li>
        <li>Address: {{$employer->physicalAddressName}} P. O.Box {{$employer->physicalAddressBox}}, {{$employer->physicalAddressLocation}}</li>
    </ul>
</div>
<div>
    <p>Employer details</p>
    <ul>
        <li>Name: {{$employer->proxyName}}</li>
        <li>Email: {{$employer->proxyEmail}}</li>
        <li>Phone Number: +265{{$employer->proxyPhoneNumber}}</li>
    </ul>
</div>
<br>
<p>Regards</p>
</body>
</html>
