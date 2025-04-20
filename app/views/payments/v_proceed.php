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
        <a href="<?= URLROOT ?>/home">🏠 Return to Home</a>

        <?php if (isset($data['message']) && str_contains($data['message'], 'successful')): ?>
            <a href="<?= URLROOT ?>/rateReview/add">📝 Leave a Review</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>


