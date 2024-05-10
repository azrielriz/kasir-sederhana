<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir sederha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .input-group {
            margin-bottom: 10px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .struk {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
        }
        .struk h3 {
            margin-bottom: 10px;
        }
        .struk table {
            width: 100%;
            border-collapse: collapse;
        }
        .struk th, .struk td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

<?php
class Cashier {
    private $items = [];

    public function addItem($name, $price) {
        $this->items[] = array("name" => $name, "price" => $price);
    }

    public function removeItem($index) {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'];
        }
        return $total;
    }
}

session_start();

// Mengecek apakah item baru ditambahkan
if (isset($_POST["item"]) && isset($_POST["price"])) {
    if (!isset($_SESSION["cashier"])) {
        $_SESSION["cashier"] = new Cashier();
    }
    $name = $_POST["item"];
    $price = floatval($_POST["price"]);
    $_SESSION["cashier"]->addItem($name, $price);
}

// Mengecek apakah item harus dihapus
if (isset($_GET["delete"])) {
    $index = $_GET["delete"];
    if (isset($_SESSION["cashier"])) {
        $_SESSION["cashier"]->removeItem($index);
    }
}

// Mengecek apakah session cashier sudah dibuat
if (isset($_SESSION["cashier"])) {
    $cashier = $_SESSION["cashier"];
    $items = $cashier->getItems();
    $total = $cashier->getTotal();
} else {
    $items = [];
    $total = 0;
}
?>

<div class="container">
    <h2>Kasir sederhana</h2>
    <form method="post">
        <div class="input-group">
            <label for="item">Nama barang:</label>
            <input type="text" id="item" name="item" required>
        </div>
        <div class="input-group">
            <label for="price">Harga:</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <button type="submit" class="btn">Cetak</button>
    </form>
    <div class="struk">
    <h3>Struk Billing</h3>
    <table>
        <tr>
            <th>Barang</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($items as $index => $item): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo 'Rp ' . number_format($item['price'], 0, ',', '.') . ',000'; ?></td> <!-- Mengubah format harga menjadi Rupiah Indonesia -->
                <td><a href="?delete=<?php echo $index; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong><?php echo 'Rp ' . number_format($total, 0, ',', '.') . ',000'; ?></strong></td> <!-- Mengubah format total menjadi Rupiah Indonesia -->
            <td></td>
        </tr>
    </table>
</div>
</div>

</body>
</html>
