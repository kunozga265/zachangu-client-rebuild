<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loan Approval Pending</title>
    <style>
    </style>
</head>

<body>
<p>Hello, {{$proxyName}}.</p>
<br>
<p>{{$employeeName}} has applied to Zachangu Microfinance Agency to access a loan of MK{{$loanAmount}} to be deducted from salary on {{$dueDate}}. Attached is the loan schedule;</p>
<table class='my-4 w-full table-fixed'>
    <thead>
    <tr class=''>
        <th style='padding:8px 8px 8px 0'>Payment Date</th>
        <th style='padding:8px 8px 8px 0'>Opening Balance</th>
        <th style='padding:8px 8px 8px 0'>Monthly Payment</th>
        <th style='padding:8px 8px 8px 0'>Principal</th>
        <th style='padding:8px 8px 8px 0'>Interest</th>
        <th style='padding:8px 8px 8px 0'>Closing Balance</th>
    </tr>
    </thead>
    <tbody class='mt-2'>
    {!!$loanSummary!!}
    </tbody>
</table>

<p>Please confirm if {{$employeeName}} is your employee and will commit to repay this loan by {{$dueDate}}.</p>
<br>
<p>Regards</p>
<p>Zachangu Microfinance Agency</p>

</body>
</html>
