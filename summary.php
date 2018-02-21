<?php
/**
 * process.php by John Pura
 * john.pura@comcast.net
 * 
 * Produces a loan summary report 
 */
require('Form.php');

$form = new DWA\Form($_POST);

// Get form values
$monthlyPayments = $form->get('monthlyPayments');
$interestRate = $form->get('interestRate');
$loanTerm = $form->get('loanTerm');
$wholeDollar = $form->get('wholeDollar');

// Validate form data
$errors = $form->validate(
    [
        'monthlyPayments' => 'required|numeric',
        'interestRate' => 'required|numeric',
        'loanTerm' => 'required|numeric',
    ]
);

// Sanitize data
$monthlyPayments = (float) trim($monthlyPayments);
$interestRate = (float) trim($interestRate);
$loanTerm = (int) trim($loanTerm);
$wholeDollar = (boolean) $wholeDollar;

// Calculates the amount of the loan a user can afford based on his monthly payments
function calculateLoanAmount($payments, $rate, $term, $wholeDollar)
{
    // Conversions
    $rate = ($rate / 12) / 100;

    /* Use the formula 
     *  A = (P/i)[1 − (1+i)^-N]
     * 
     *  A is the amount of the loan
     *  P is the monthly payments
     *  i is the interest rate
     *  N is the number of payments 
     */
    $loanAmount = ($payments / $rate) * (1 - pow((1 + $rate), -$term));
    // check if user wants to round off to the nearest dollar
    if ($wholeDollar == true) {
        $loanAmount = round ($loanAmount, 0, PHP_ROUND_HALF_UP);
        return($loanAmount);
    }
    else {
        $loanAmount = round ($loanAmount, 2);
        return($loanAmount);
    }
}

$loanAmount = calculateLoanAmount ($monthlyPayments, $interestRate, $loanTerm, $wholeDollar);

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Project 2</title>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
        <!-- CSS Libraries-->
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
        <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="page-wrapper">
            <h1 class="is-size-1 has-text-grey-darker has-text-left is-uppercase">Loan Summary</h1>
            <table class="table is-hoverable is-fullwidth">
                <thead>
                <tr>
                    <th>Montly Payments</th>
                    <th>Interest Rate</th>
                    <th>Number of Payments</th>
                    <th>Loan Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>$<?php echo $monthlyPayments; ?></td>
                    <td><?php echo $interestRate; ?>%</td>
                    <td><?php echo $loanTerm; ?></td>
                    <td>$<?php echo $loanAmount; ?></td>
                </tr>
                </tbody>
            </table>
        <div>
    </body>
</html>


