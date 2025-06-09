<?php
function cidrToMask($cidr) {
    return long2ip(-1 << (32 - $cidr));
}

function calculateSubnetDetails($ip, $cidr) {
    $ipLong = ip2long($ip);
    $mask = ip2long(cidrToMask($cidr));
    $network = $ipLong & $mask;
    $broadcast = $network | ~$mask;

    $firstUsable = $network + 1;
    $lastUsable = $broadcast - 1;

    $totalHosts = pow(2, 32 - $cidr);
    $usableHosts = $totalHosts - 2;

    return [
        'IP' => $ip,
        'CIDR' => $cidr,
        'Subnet Mask' => long2ip($mask),
        'Network Address' => long2ip($network),
        'Broadcast Address' => long2ip($broadcast),
        'Total Hosts' => $totalHosts,
        'Usable Hosts' => $usableHosts,
        'First Usable IP' => long2ip($firstUsable),
        'Last Usable IP' => long2ip($lastUsable),
        'Usable IPs' => array_map('long2ip', range($firstUsable, $lastUsable))
    ];
}

// Handle form input
$details = [];
if (isset($_POST['ip']) && isset($_POST['cidr'])) {
    $ip = $_POST['ip'];
    $cidr = intval($_POST['cidr']);
    if (filter_var($ip, FILTER_VALIDATE_IP) && $cidr >= 1 && $cidr <= 30) {
        $details = calculateSubnetDetails($ip, $cidr);
    } else {
        $error = "Invalid IP or CIDR range!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Subnet Calculator</title>
    <style>
        body { font-family: Arial; background: #1e1e2f; color: white; padding: 20px; }
        input, select, button { padding: 5px; margin: 5px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #444; text-align: left; }
        th { background-color: #333; }
    </style>
</head>
<body>
    <h2>PHP Subnet Calculator</h2>
    <form method="POST">
        <label>IP Address:</label>
        <input type="text" name="ip" required placeholder="192.168.0.1" value="<?= htmlspecialchars($_POST['ip'] ?? '') ?>">
        <label>CIDR:</label>
        <input type="number" name="cidr" min="1" max="30" required value="<?= htmlspecialchars($_POST['cidr'] ?? '') ?>">
        <button type="submit">Calculate</button>
    </form>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php elseif (!empty($details)): ?>
        <h3>Subnet Details</h3>
        <table>
            <?php foreach ($details as $key => $value): ?>
                <?php if ($key !== 'Usable IPs'): ?>
                    <tr><th><?= $key ?></th><td><?= $value ?></td></tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>

        <h3>Usable IP Addresses</h3>
        <table>
            <tr><th>#</th><th>IP Address</th></tr>
            <?php foreach ($details['Usable IPs'] as $i => $ip): ?>
                <tr><td><?= $i + 1 ?></td><td><?= $ip ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
