<div class="modal fade" id="addPaymentModal"  role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary ">
        <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
        <form id="addPaymentForm">
      
        <div class="form-group">
  <label for="fuelSale">Fuel Sale</label>
  <select class="form-control select2" style="width: 100%;" id="fuelSalesID" name="fuelSalesID">
    <option disabled value="">Select Fuel Sale</option>
    <?php
    $query = "SELECT SalesID, SalesDate, CustomerName, TankerID FROM FuelSales WHERE Balance > 0";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
  $fuelSaleID = $row['SalesID'];
  $saleDate = $row['SalesDate'];
  $tankerSalesID = $row['TankerID'];
  $customerName = $row['CustomerName'];
  echo "<option value=\"$fuelSaleID\" data-tanker-id=\"$tankerSalesID\" data-customer-name=\"$customerName\">$fuelSaleID - $customerName - $saleDate</option>";
}

    }
    ?>
  </select>
</div>


 <div class="form-group">
            <label for="customerName">Customer Name</label>
            <select class="form-control" id="customerName" name="customerName" style="width: 100%;" >
          <option disabled selected value="">Select Customer Name</option>
                   <?php
          $query = "SELECT CustomerID, CustomerName FROM Customers ";
          $result = mysqli_query($connect, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $CustomerName = $row['CustomerName'];
              $CustomerId = $row['CustomerID'];
              echo "<option value=\"$CustomerName\"data-customer-id=\"$CustomerId\">$CustomerName</option>";

            }
          }
          ?>

            </select>
          </div>
  

  <div class="form-group">
  <label for="fuelSaleAmount">Balance</label>
  <input type="text" class="form-control" name="fuelSaleAmount" id="fuelSaleAmount" readonly>
  <small id="balanceText" class="form-text text-muted"></small> <!-- Add a new element to display the balance -->
</div>
 <div class="form-group">
            <label for="paymentAmount">Payment Amount</label>
            <input type="text" class="form-control" id="paymentAmount" onkeyup="formatAmount(this)" name="paymentAmount" step="0.01" >
          </div>

 
          <div class="form-group">
            <label for="paymentDate">Payment Date</label>
            <input type="text" class="form-control" id="paymentDate" name="paymentDate" >
          </div>
          <div class="form-group">
  <label for="bankName">Bank Name</label>
  <select class="form-control select2" style="width: 100%;" id="bankName" name="bankName">
    <option disabled selected value="">Select Bank</option>
    <option value="Access Bank">Access Bank</option>
    <option value="Zenith Bank">Zenith Bank</option>
    <option value="Guaranty Trust Bank">Guaranty Trust Bank</option>
    <option value="United Bank for Africa">United Bank for Africa (UBA)</option>
    <option value="First Bank of Nigeria">First Bank of Nigeria</option>
    <option value="Ecobank Nigeria">Ecobank Nigeria</option>
    <option value="Stanbic IBTC Bank">Stanbic IBTC Bank</option>
    <option value="Fidelity Bank">Fidelity Bank</option>
    <option value="Union Bank of Nigeria">Union Bank of Nigeria</option>
    <option value="Sterling Bank">Sterling Bank</option>
    <option value="Keystone Bank">Keystone Bank</option>
    <option value="Polaris Bank">Polaris Bank</option>
    <option value="Wema Bank">Wema Bank</option>
    <option value="Heritage Bank">Heritage Bank</option>
    <option value="Unity Bank">Unity Bank</option>
    <option value="Jaiz Bank">Jaiz Bank</option>
    <option value="Providus Bank">Providus Bank</option>
  </select>
</div>         
          <div class="form-group">
  <label for="tankerSalesID">Tanker Sales ID</label>
  <input type="text" class="form-control" id="tankerSalesID" readonly name="tankerSalesID" >
  </div>
            <input  type="hidden" class="form-control" id="customerNameId" name="customerID" required>
          <!-- Submit button -->
          <button type="submit" class="btn btn-primary">Add Payment</button>
        </form>
      </div>
    </div>
  </div>
</div>
