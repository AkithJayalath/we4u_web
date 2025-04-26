<!-- views/payments/v_result.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Payment Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            text-align: center;
            padding-top: 100px;
        }

        .box {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: inline-block;
        }

        h2 {
            color: #2c5282;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #2c5282;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        a:hover {
            background-color: #1a3c66;
        }
    </style>
</head>
<body>


<div class="box">
    <h2><?php echo isset($data['message']) ? $data['message'] : 'Payment processed'; ?></h2>

    <div class="btn-group">
        <a href="<?= URLROOT ?>caregivers/viewRequests">🏠 Return to Home</a>

        <?php if (isset($data['message']) && str_contains($data['message'], 'successful')): ?>
            <a href="#" onclick="showReceipt()">🧾 View Receipt</a>
        <?php endif; ?>
    </div>
</div>

<style>
.receipt-popup {
      display: flex;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .receipt-content {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      width: 90%;
      max-width: 700px;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      padding: 0;
    }

    .receipt-header {
      background-color: #2c5282;
      color: white;
      padding: 20px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .receipt-header h2 {
      margin: 0;
      font-size: 24px;
    }

    .receipt-logo {
      background-color: white;
      border-radius: 5px;
      padding: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .receipt-logo img {
      height: 40px;
      width: auto;
    }

    .receipt-body {
      padding: 20px;
    }

    .receipt-info {
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 15px;
    }

    .receipt-details, .receipt-payment {
      margin-bottom: 20px;
    }

    .receipt-details h3, .receipt-payment h3 {
      color: #2c5282;
      margin-bottom: 10px;
      font-size: 18px;
    }

    .receipt-details table, .receipt-payment table {
      width: 100%;
      border-collapse: collapse;
    }

    .receipt-details td, .receipt-payment td {
      padding: 8px 0;
      border-bottom: 1px solid #eee;
    }

    .receipt-details td:first-child, .receipt-payment td:first-child {
      font-weight: 600;
      width: 40%;
    }

    .total-row {
      font-weight: bold;
      font-size: 18px;
      border-top: 2px solid #eee;
    }

    .payment-status {
      font-weight: bold;
      padding: 3px 8px;
      border-radius: 4px;
    }

    .payment-status.success {
      background-color: #d4edda;
      color: #155724;
    }

    .receipt-note {
      font-size: 14px;
      color: #666;
      font-style: italic;
      margin-top: 20px;
      text-align: center;
    }

    .receipt-footer {
      padding: 15px 20px;
      background-color: #f9f9f9;
      border-top: 1px solid #eee;
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }

    .receipt-actions {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 15px;
    }

    .download-btn, .close-btn {
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: all 0.3s ease;
    }

    .download-btn {
      background-color: #0B63F8;
      color: white;
    }

    .download-btn:hover {
      background-color: #0953D6;
    }

    .close-btn {
      background-color: #EE1D52;
      color: white;
    }

    .close-btn:hover {
      background-color: #C31945;
    }

    .receipt-contact {
      text-align: center;
      font-size: 14px;
      color: #777;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .receipt-header {
        flex-direction: column;
        text-align: center;
      }
      
      .receipt-logo {
        margin-top: 10px;
      }
      
      .receipt-actions {
        flex-direction: column;
      }
      
      .download-btn, .close-btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>

 


<div id="receiptPopup" class="receipt-popup">
    <div class="receipt-content">
      <div class="receipt-header">
        <h2>Payment Receipt</h2>
        <div class="receipt-logo">
          <span style="color:#2c5282; font-weight:bold;">WE4U</span>
        </div>
      </div>
      
      <div class="receipt-body">
        <div class="receipt-info">
          <p><strong>Transaction ID:</strong> <span id="transactionId"><?= $data['receipt']['request_id'] ?? 'N/A' ?></span></p>
          <?php

            date_default_timezone_set('Asia/Colombo');


            ?>
          <p><strong>Date:</strong> <span id="receiptDate"><?= date('F j, Y, g:i A') ?></span></p>
        </div>
        
        <div class="receipt-details">
          <h3>Service Details</h3>
          <table>
            <tr>
              <td>Service:</td>
              <td><?= $data['receipt']['service'] ?? 'N/A' ?></td>
            </tr>
            <tr>
              <td>Client (payer):</td>
              <td><?= $data['receipt']['payer'] ?? 'N/A' ?></td>
            </tr>
            <tr>
              <td>Caregiver:</td>
              <td><?= $data['receipt']['provider'] ?? 'N/A' ?></td>
            </tr>
            <tr>
              <td>Service Type:</td>
              <td><?= $data['receipt']['service_type'] ?? 'N/A' ?></td>
            </tr>
            
          </table>
        </div>
        
        <div class="receipt-payment">
          <h3>Payment Information</h3>
          <table>
            
            <tr class="total-row">
              <td>Total Payment:</td>
              <td>Rs.<?= number_format($data['receipt']['amount'] ?? 0, 2) ?></td>            
            </tr>
            <tr>
              <td>Payment Status:</td>
              <td><span class="payment-status success">Successful</span></td>
            </tr>
          </table>
        </div>
        
        <div class="receipt-note">
          <p>Thank you for choosing ElderCare Services. This is an electronically generated receipt.</p>
        </div>
      </div>
      
      <div class="receipt-footer">
        <div class="receipt-actions">
          <button id="downloadReceipt" class="download-btn" onclick="downloadReceipt()"><i class="fas fa-download"></i> Download Receipt</button>
          <button id="closeReceipt" class="close-btn" onclick="closeReceipt()"><i class="fas fa-times"></i> Close</button>
        </div>
        <div class="receipt-contact">
          <p>For any assistance, contact our support team: WE4U (1-800-353-3722)</p>
        </div>
      </div>
    </div>
  </div>


<script>

function showReceipt() {
        document.getElementById('receiptPopup').style.display = 'flex';
    }
function closeReceipt() {
        document.getElementById('receiptPopup').style.display = 'none';
    }


function printReceipt() {
  var content = document.getElementById('receiptContent').innerHTML;
  var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Payment Receipt</title>');
    printWindow.document.write('<style>body{font-family:Arial,sans-serif;} table{width:100%;border-collapse:collapse;} td{padding:8px;border-bottom:1px solid #eee;} .total-row{font-weight:bold;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

function closeModal() {
  document.getElementById('receiptModal').style.display = 'none';
}

// Auto show popup on page load
window.onload = function() {
  document.getElementById('receiptModal').style.display = 'block';
};

// Function to download the receipt as a PDF
function downloadReceipt() {
    var receiptContent = document.querySelector('.receipt-content').innerHTML;
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Payment Receipt</title>');
    printWindow.document.write('<style>body{font-family:Arial,sans-serif;} table{width:100%;border-collapse:collapse;} td{padding:8px;border-bottom:1px solid #eee;} .total-row{font-weight:bold;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(receiptContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}





</script>



</body>
</html>


