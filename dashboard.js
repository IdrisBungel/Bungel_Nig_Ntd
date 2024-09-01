  function toggleDarkMode() {
    var body = document.querySelector('body');
    body.classList.toggle('dark-mode');
  }

$(document).ready(function() {

  // Fetch outstanding payments data for the table
  $.ajax({
    url: 'fetch_dashboard_payments.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      // Populate the outstanding payments table with the retrieved data
      populateOutstandingPaymentsTable(response);
    },
    error: function(xhr, status, error) {
      console.log('Error fetching outstanding payments data: ' + error);
    }
  });

  // Fetch total debt data for the table
  $.ajax({
    url: 'fetch_total_debt.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      // Populate the total debt table with the retrieved data
      populateTotalDebtTable(response);
    },
    error: function(xhr, status, error) {
      console.log('Error fetching total debt data: ' + error);
    }
  });
});
// Function to fetch fuel sales data and create the chart
function fetchFuelSalesDataAndCreateChart() {
  $.ajax({
    url: 'fetch_dashboard_fuel_sales.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      if (data.length > 0) {
        createFuelSalesChart(data);
      } else {
        // No data available, show a message or handle appropriately
      }
    },
    error: function (error) {
      // Handle error
      console.log('Error fetching fuel sales data: ' + error);
    }
  });
}
// Function to create the fuel sales chart
function createFuelSalesChart(data) {
  // Array of month names
  var monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  // Process the data to extract labels and datasets for petrol and gas
  var labels = [];
  var petrolSalesData = [];
  var gasSalesData = [];
  for (var i = 0; i < data.length; i++) {
    var monthNumber = data[i].month;
    var monthName = monthNames[monthNumber - 1]; // Subtract 1 since array is 0-based

    labels.push(monthName);
    if (data[i].fuelType === 'Petrol') {
      petrolSalesData.push(data[i].totalSales);
      gasSalesData.push(0);
    } else if (data[i].fuelType === 'Gas') {
      petrolSalesData.push(0);
      gasSalesData.push(data[i].totalSales);
    } else {
      // Handle other fuel types if necessary
    }
  }

  // Create the chart using Chart.js
  var ctx = document.getElementById('fuelSalesChart').getContext('2d');
  var fuelSalesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Petrol Sales (Liters)',
        data: petrolSalesData,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }, {
        label: 'Gas Sales (Liters)',
        data: gasSalesData,
        backgroundColor: 'rgba(255, 99, 132, 0.6)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}

// Fetch fuel sales data and create the chart on page load
$(document).ready(function () {
  fetchFuelSalesDataAndCreateChart();
});



// Function to populate the outstanding payments table
function populateOutstandingPaymentsTable(data) {
  var tableBody = $('#outstandingPaymentsTable tbody');
  tableBody.empty();

  for (var i = 0; i < data.length; i++) {
    var row = '<tr>' +
              '<td>' + data[i].CustomerName + '</td>' +
              '<td>' + data[i].AmountDue + '</td>' +
              '<td>' + data[i].InvoiceDate + '</td>' +
              '</tr>';
    tableBody.append(row);
  }
}

// Function to populate the total debt table
function populateTotalDebtTable(data) {
  var tableBody = $('#totalDebtTable tbody');
  tableBody.empty();

  for (var i = 0; i < data.length; i++) {
    var row = '<tr>' +
              '<td>' + data[i].CustomerName + '</td>' +
              '<td>' + data[i].TotalDebt + '</td>' +
              '</tr>';
    tableBody.append(row);
  }
}

// Function to fetch recent tanker sales data and populate the table
function fetchRecentTankerSales() {
  $.ajax({
    url: 'fetch_recent_tanker_sales.php', // Replace with the PHP file to fetch recent tanker sales
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      // Populate the table with the retrieved data
      var tableBody = $('#recentTankerSalesTable tbody');
      tableBody.empty();

      for (var i = 0; i < response.length; i++) {
        var tankerSales = response[i];
        var row = '<tr>' +
          '<td>' + tankerSales.DriverName + '</td>' +
          '<td>' + tankerSales.CustomerName + '</td>' +
          '<td>' + tankerSales.FuelType + '</td>' +
          '<td>' + tankerSales.SalesDate + '</td>' +
          '<td>' + tankerSales.TotalAmount + '</td>' +
          '<td>₦' + tankerSales.Balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '</td>' +
          '</tr>';
        tableBody.append(row);
      }
    },
    error: function (error) {
      console.log('Error fetching recent tanker sales data: ' + error);
    }
  });
}

// Fetch recent tanker sales on page load
fetchRecentTankerSales();
