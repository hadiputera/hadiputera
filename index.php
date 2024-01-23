<?php
function calculateElectricityRates($voltage, $current, $currentRate) {
    // Calculate Power in kW
    $power = ($voltage * $current) / 1000;

    // Calculate Rate
    $rate = $currentRate / 100;

    return [
        'power' => $power,
        'rate' => $rate
    ];
}

function calculateHourlyRates($result, $hour) {
	// Calculate Energy per Hour
    $energyPerHour = $result['power'] * $hour;
	
	// Calculate Total(RM) per Hour
    $totalPerHour = $energyPerHour * $result['rate'];

    return [
        'energyPerHour' => $energyPerHour,
        'totalPerHour' => $totalPerHour
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $voltage = isset($_POST['voltage']) ? floatval($_POST['voltage']) : 0;
    $current = isset($_POST['current']) ? floatval($_POST['current']) : 0;
    $currentRate = isset($_POST['currentRate']) ? floatval($_POST['currentRate']) : 0;

    // Calculate electricity power and rate
    $result = calculateElectricityRates($voltage, $current, $currentRate);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Rate Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Electricity Rate Calculator</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="voltage">Voltage (V):</label>
                <input type="number" class="form-control" id="voltage" step="0.01" name="voltage" required>
            </div>
            <div class="form-group">
                <label for="current">Current (A):</label>
                <input type="number" class="form-control" id="current" step="0.01" name="current" required>
            </div>
            <div class="form-group">
                <label for="currentRate">Current Rate (sen/kWh):</label>
                <input type="number" class="form-control" id="currentRate" step="0.01" name="currentRate" required>
            </div>
            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>

        <?php if (isset($result)): ?>
            <h3 class="mt-4">Results:</h3>
			<div class="card bg-light text-dark">
				<div class="card-body">
				<p class="font-weight-bolder">POWER: <?php echo $result['power']; ?> kW</p>
				<p class="font-weight-bolder">RATE: <?php echo $result['rate']; ?> RM</p>
				</div>
			</div>
            <table class="table">
                <thead>
                  <tr>
					<th scope="col">#</th>
                    <th scope="col">Hour</th>
                    <th scope="col">Energy (kWh)</th>
                    <th scope="col">Total (RM)</th>
                  </tr>
                </thead>
                <?php
                  for ($hour = 1; $hour <= 24; $hour++) {
                      $hourlyRates = calculateHourlyRates($result, $hour);   
                ?>
                <tbody>
                <tr>
                <?php 
					echo '<th scope="row">'.$hour.'</th>';
                    echo '<td>'.$hour.'</td>';
                    echo '<td>'.number_format($hourlyRates['energyPerHour'], 5).'</td>';
					echo '<td>'.number_format($hourlyRates['totalPerHour'], 2).'</td>';
                 }
				?>
                </tr>
                </tbody>
			</table> 
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>