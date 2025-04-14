<?php
/* Bank Transfer */
$PageSecurity = 1;
include('includes/session.php');
include('includes/DefinePaymentClass.php');

$Title = _('Bank Transfer');
include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
    '/images/transactions.png" title="', $Title, '" /> ', $Title, '</p>';

include('includes/SQL_CommonFunctions.inc');

// Initialize session for bank transfer
if (empty($_GET['identifier'])) {
    $identifier = date('U');
} else {
    $identifier = $_GET['identifier'];
}

// Handle form submission
if (isset($_POST['CommitBatch'])) {
    $SenderBankAccount = $_POST['SenderBankAccount'];
    $ReceiverBankAccount = $_POST['ReceiverBankAccount'];
    $Amount = filter_number_format($_POST['Amount']);
    $DateBanked = $_POST['DateBanked']; // Raw date input from the form
    $Currency = $_POST['Currency'];
    $Reference = $_POST['BankTransRef'];
    $Description = $_POST['Narrative'];
    $CreatedBy = $_SESSION['UserID'];

    // Validate and format the date
    $DateBanked = date('Y-m-d', strtotime($DateBanked)); // Convert to YYYY-MM-DD format

    // Check if the date is valid
    if (!$DateBanked || $DateBanked === '1970-01-01') {
        prnMsg(_('Invalid date format. Please enter a valid date.'), 'error');
        exit;
    }

    // Fetch the sender's bank account balance
    $SQL = "SELECT accountcode, bankaccountname, currcode,
               (SELECT SUM(amount) FROM banktrans WHERE banktrans.bankact = bankaccounts.accountcode) AS balance
        FROM bankaccounts
        WHERE accountcode = '" . $SenderBankAccount . "'";
    $Result = DB_query($SQL);
    $SenderBank = DB_fetch_array($Result);

    // Check if the sender's balance is negative
    if ($SenderBank['balance'] < 0) {
        prnMsg(_('The sender\'s account balance is negative. Transfer cannot proceed.'), 'error');
        exit;
    }

    // Check if the sender has sufficient funds for the transfer
    if ($SenderBank['balance'] < $Amount) {
        prnMsg(_('Insufficient funds in the sender\'s bank account. Transfer cannot proceed.'), 'error');
        exit;
    }

    // Insert transfer details into banktransfers table
    $SQL = "INSERT INTO banktransfers (
                senderbankaccount,
                receiverbankaccount,
                amount,
                currency,
                datebanked,
                reference,
                description,
                createdby
            ) VALUES (
                '" . $SenderBankAccount . "',
                '" . $ReceiverBankAccount . "',
                '" . $Amount . "',
                '" . $Currency . "',
                '" . $DateBanked . "',
                '" . $Reference . "',
                '" . $Description . "',
                '" . $CreatedBy . "'
            )";
    DB_query($SQL);

    // Deduct amount from sender account
    $SQL = "INSERT INTO banktrans (
                transno,
                type,
                bankact,
                ref,
                transdate,
                banktranstype,
                amount,
                currcode
            ) VALUES (
                '" . GetNextTransNo(1) . "',
                1,
                '" . $SenderBankAccount . "',
                '" . $Reference . "',
                '" . $DateBanked . "',
                'Transfer',
                '" . -$Amount . "',
                '" . $Currency . "'
            )";
    DB_query($SQL);

    // Add amount to receiver account
    $SQL = "INSERT INTO banktrans (
                transno,
                type,
                bankact,
                ref,
                transdate,
                banktranstype,
                amount,
                currcode
            ) VALUES (
                '" . GetNextTransNo(1) . "',
                1,
                '" . $ReceiverBankAccount . "',
                '" . $Reference . "',
                '" . $DateBanked . "',
                'Transfer',
                '" . $Amount . "',
                '" . $Currency . "'
            )";
    DB_query($SQL);
    // Redirect to the same page to prevent resubmission
    echo '<script>
    alert("Bank transfer has been successfully processed.");
    setTimeout(function() {
        window.location.href = "BankTransfer.php";
    }, 1000); // Redirect after 2 seconds
</script>';
exit;
    // Deduct amount from sender's GL account
    $SQL = "INSERT INTO gltrans (
                type,
                typeno,
                trandate,
                periodno,
                account,
                narrative,
                amount
            ) VALUES (
                1, -- Transaction type (e.g., 1 for bank transfer)
                '" . GetNextTransNo(1) . "', -- Transaction number
                '" . $DateBanked . "', -- Transaction date
                '" . GetPeriod($DateBanked) . "', -- Accounting period
                '" . $SenderBankAccount . "', -- Sender's GL account
                'Transfer to " . $ReceiverBankAccount . "', -- Description
                '" . -$Amount . "' -- Debit amount (negative for deduction)
            )";
    DB_query($SQL);

    // Add amount to receiver's GL account
    $SQL = "INSERT INTO gltrans (
                type,
                typeno,
                trandate,
                periodno,
                account,
                narrative,
                amount
            ) VALUES (
                1, -- Transaction type (e.g., 1 for bank transfer)
                '" . GetNextTransNo(1) . "', -- Transaction number
                '" . $DateBanked . "', -- Transaction date
                '" . GetPeriod($DateBanked) . "', -- Accounting period
                '" . $ReceiverBankAccount . "', -- Receiver's GL account
                'Transfer from " . $SenderBankAccount . "', -- Description
                '" . $Amount . "' -- Credit amount (positive for addition)
            )";
    DB_query($SQL);

    

    // Fetch the most recent transaction
    $SQL = "SELECT * FROM banktransfers 
            WHERE createdby = '" . $CreatedBy . "' 
            ORDER BY createdon DESC 
            LIMIT 1";
    $Result = DB_query($SQL);
    $RecentTransaction = DB_fetch_array($Result);

    // Display the most recent transaction
    if ($RecentTransaction) {
        echo '<div class="recent-transaction">';
        echo '<h3>Recent Transaction Details</h3>';
        echo '<table class="selection">';
        echo '<tr><td><strong>Sender Bank Account:</strong></td><td>' . $RecentTransaction['senderbankaccount'] . '</td></tr>';
        echo '<tr><td><strong>Receiver Bank Account:</strong></td><td>' . $RecentTransaction['receiverbankaccount'] . '</td></tr>';
        echo '<tr><td><strong>Amount:</strong></td><td>' . number_format($RecentTransaction['amount'], 2) . ' ' . $RecentTransaction['currency'] . '</td></tr>';
        echo '<tr><td><strong>Date:</strong></td><td>' . $RecentTransaction['datebanked'] . '</td></tr>';
        echo '<tr><td><strong>Reference:</strong></td><td>' . $RecentTransaction['reference'] . '</td></tr>';
        echo '<tr><td><strong>Description:</strong></td><td>' . $RecentTransaction['description'] . '</td></tr>';
        echo '</table>';
        echo '</div>';
    }

    prnMsg(_('Bank transfer has been successfully processed'), 'success');

}

// Display the form
echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?identifier=', urlencode($identifier), '" method="post">
    <div>
        <input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
       
        <table class="selection">
            <tr>
                <td>Sender Bank Account:</td>
                <td>
                    <select name="SenderBankAccount" id="SenderBankAccount" required>';
$SQL = "SELECT accountcode, bankaccountname, currcode,
               (SELECT SUM(amount) FROM banktrans WHERE banktrans.bankact = bankaccounts.accountcode) AS balance
        FROM bankaccounts";
$result = DB_query($SQL);
while ($row = DB_fetch_array($result)) {
    echo '<option value="', $row['accountcode'], '" data-currency="', $row['currcode'], '" data-balance="', $row['balance'], '">', $row['bankaccountname'], '</option>';
}
echo '</select>
                </td>
            </tr>
            <tr>
                <td>Current Balance:</td>
                <td><span id="CurrentBalance">0.00</span></td>
            </tr>
            <tr>
                <td>Currency:</td>
                <td><input type="text" name="Currency" id="Currency" readonly /></td>
            </tr>
            <tr>
                <td>', _('Receiver Bank Account'), ':</td>
                <td><select name="ReceiverBankAccount" required>';
// Fetch receiver bank accounts
$SQL = "SELECT accountcode, bankaccountname FROM bankaccounts";
$result = DB_query($SQL);
while ($row = DB_fetch_array($result)) {
    echo '<option value="', $row['accountcode'], '">', $row['bankaccountname'], '</option>';
}
echo '</select></td>
            </tr>
            <tr>
                <td>', _('Date Banked'), ':</td>
                <td><input type="date" name="DateBanked" required /></td>
            </tr>
            <tr>
                <td>', _('Amount'), ':</td>
                <td><input type="number" name="Amount" id="Amount" required /></td>
            </tr>
            <tr>
                <td>', _('Reference'), ':</td>
                <td><input type="text" name="BankTransRef" maxlength="50" placeholder="Reference" /></td>
            </tr>
            <tr>
                <td>', _('Description'), ':</td>
                <td><textarea name="Narrative" rows="3" cols="40" placeholder="Enter a description"></textarea></td>
            </tr>
            <tr>
                <td  style="text-align: center;">
                    <button type="submit" name="CommitBatch" class="btn btn-primary"> Transfer</button>
                </td>
                <td>
                    <span id="ErrorMessage" style="color: red; display: none;">Insufficient funds!</span>
                    <span id="SuccessMessage" style="color: green; display: none;">Transfer details look good!</span>
        </div>
    </div>
</form>';


include('includes/footer.php');
?>