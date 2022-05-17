<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approval Loan</title>
</head>
<body>
<p>Hello, {{$loan->firstName}} {{$loan->lastName}}!</p>
<br>
<p>Zachangu Loans is pleased to inform you that your loan of MK{{$loan->amount}} has been approved. On {{$dueDate}}, you will be requested to pay MK{{$loanAmount}}</p>
<p>You may contact us for more details.</p>
<br>
<p>Regards.</p>
<p>Zachangu Microfinance Agency</p>

</body>
</html>
