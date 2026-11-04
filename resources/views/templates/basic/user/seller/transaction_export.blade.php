<h1>WISERLANCE</h1>
<table>
    <thead>
    <tr>
        <th style="font-weight: bold;font-size:18px">Sr No</th>
        <th style="font-weight: bold;font-size:18px">TRX</th>
        <th style="font-weight: bold;font-size:18px">Amount</th>
        <th style="font-weight: bold;font-size:18px">Charge</th>
        <th style="font-weight: bold;font-size:18px">Post Balance</th>
        <th style="font-weight: bold;font-size:18px">Details</th>
    </tr>
    </thead>
    <tbody>
        @php
            $index=1;
        @endphp
        @foreach($transactions as $key=>$transaction)
        <tr>
            <td style="font-size:14px">{{ $index }}</td>
            <td style="font-size:14px">{{ $transaction['trx'] }}</td>
            <td style="font-size:14px">${{ $transaction['amount'] }}</td>
            <td style="font-size:14px">${{ $transaction['charge'] }}</td>
            <td style="font-size:14px">${{ $transaction['post_balance'] }}</td>
            <td style="font-size:14px">{{ $transaction['details'] }}</td>
        </tr>
        @php
            $index++;
        @endphp
        @endforeach
    </tbody>
</table>
