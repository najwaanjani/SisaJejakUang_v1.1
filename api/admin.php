<?php
// api/admin.php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

function writeLog($pdo, $actor, $action, $status = 'success') {
    try {
        $stmt = $pdo->prepare("INSERT INTO tb_system_logs (actor, action, status) VALUES (?, ?, ?)");
        $stmt->execute([$actor, $action, $status]);
    } catch (Exception $e) {
        // Abaikan error log agar tidak mengganggu response utama
    }
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // writeLog($pdo, $_SESSION['user_id'] ?? 'Unknown', 'access_denied', 'error');
    echo json_encode(['status' => 'error', 'message' => 'Forbidden. Akses khusus Administrator.']);
    exit;
}

$action = $_GET['action'] ?? 'logs';

try {
    if ($action === 'logs') {
        // Ambil Log Audit Sistem
        $stmt = $pdo->query("SELECT * FROM tb_system_logs ORDER BY id DESC LIMIT 100");
        $logs = $stmt->fetchAll();

        echo json_encode(['status' => 'success', 'data' => $logs]);
        exit;
    }

    elseif ($action === 'users') {
        // Ambil Daftar Pengguna & Status Kesehatan Finansial
        $stmt = $pdo->query("
            SELECT 
                u.id, 
                u.name, 
                u.email, 
                COALESCE(SUM(b.saldo_saat_ini), 0) as balance, 
                COALESCE(SUM(b.saldo_awal), 0) as initial,
                (SELECT COALESCE(SUM(nominal), 0) FROM tb_transaksi WHERE user_id = u.id) as expense
            FROM tb_user u 
            LEFT JOIN tb_buku_tabungan b ON u.id = b.user_id 
            WHERE u.role = 'user' 
            GROUP BY u.id 
            ORDER BY u.name ASC
        ");
        $users = $stmt->fetchAll();

        $result = [];
        foreach ($users as $u) {
            $initial = (float) $u['initial'];
            $expense = (float) $u['expense'];
            $healthScore = $initial > 0 ? max(0, min(100, round((1 - ($expense / $initial)) * 100))) : 100;
            
            $result[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'balance' => (float) $u['balance'],
                'health_score' => $healthScore
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $result]);
        exit;
    }

    elseif ($action === 'user_summary') {
        // Ringkasan AGREGAT finansial satu user — SENGAJA tidak mengembalikan
        // daftar transaksi mentah (tanggal/keterangan/nominal per transaksi)
        // demi menjaga privasi user. Admin hanya melihat pola, bukan detail belanja.
        $targetId = $_GET['id'] ?? null;
        if (!$targetId) {
            echo json_encode(['status' => 'error', 'message' => 'ID user tidak ditemukan.']);
            exit;
        }

        // Pastikan target adalah user biasa (bukan admin lain)
        $stmtUser = $pdo->prepare("SELECT id, name, email FROM tb_user WHERE id = ? AND role = 'user'");
        $stmtUser->execute([$targetId]);
        $targetUser = $stmtUser->fetch();
        if (!$targetUser) {
            echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan.']);
            exit;
        }

        // Total pengeluaran bulan ini
        $stmtMonth = $pdo->prepare("
            SELECT COALESCE(SUM(nominal), 0) as total
            FROM tb_transaksi
            WHERE user_id = ? AND MONTH(tanggal_transaksi) = MONTH(CURDATE()) AND YEAR(tanggal_transaksi) = YEAR(CURDATE())
        ");
        $stmtMonth->execute([$targetId]);
        $totalBulanIni = (float) $stmtMonth->fetch()['total'];

        // Breakdown Kebutuhan vs Keinginan (bulan ini)
        $stmtPrio = $pdo->prepare("
            SELECT prioritas, COALESCE(SUM(nominal), 0) as total
            FROM tb_transaksi
            WHERE user_id = ? AND MONTH(tanggal_transaksi) = MONTH(CURDATE()) AND YEAR(tanggal_transaksi) = YEAR(CURDATE())
            GROUP BY prioritas
        ");
        $stmtPrio->execute([$targetId]);
        $prioRows = $stmtPrio->fetchAll();
        $needs = 0; $wants = 0;
        foreach ($prioRows as $p) {
            if ($p['prioritas'] === 'Kebutuhan') $needs = (float) $p['total'];
            if ($p['prioritas'] === 'Keinginan') $wants = (float) $p['total'];
        }

        // Kategori pengeluaran terbesar (bulan ini)
        $stmtTopCat = $pdo->prepare("
            SELECT k.nama_kategori, SUM(t.nominal) as total
            FROM tb_transaksi t
            JOIN tb_kategori k ON t.kategori_id = k.id
            WHERE t.user_id = ? AND MONTH(t.tanggal_transaksi) = MONTH(CURDATE()) AND YEAR(t.tanggal_transaksi) = YEAR(CURDATE())
            GROUP BY k.id
            ORDER BY total DESC
            LIMIT 1
        ");
        $stmtTopCat->execute([$targetId]);
        $topCat = $stmtTopCat->fetch();

        // Skor kesehatan finansial (rumus sama seperti action=users)
        $stmtHealth = $pdo->prepare("
            SELECT 
                COALESCE(SUM(b.saldo_awal), 0) as initial,
                (SELECT COALESCE(SUM(nominal), 0) FROM tb_transaksi WHERE user_id = ?) as expense
            FROM tb_buku_tabungan b WHERE b.user_id = ?
        ");
        $stmtHealth->execute([$targetId, $targetId]);
        $h = $stmtHealth->fetch();
        $initial = (float) $h['initial'];
        $expense = (float) $h['expense'];
        $healthScore = $initial > 0 ? max(0, min(100, round((1 - ($expense / $initial)) * 100))) : 100;

        // Status kepatuhan anggaran (berapa dari total pos anggaran yang masih di bawah limit)
        $stmtBudgets = $pdo->prepare("
            SELECT a.id, a.batas_limit, COALESCE(SUM(t.nominal), 0) as terpakai
            FROM tb_anggaran a
            JOIN tb_buku_tabungan b ON a.buku_tabungan_id = b.id
            LEFT JOIN tb_transaksi t ON t.anggaran_id = a.id
            WHERE b.user_id = ?
            GROUP BY a.id
        ");
        $stmtBudgets->execute([$targetId]);
        $budgetRows = $stmtBudgets->fetchAll();
        $totalBudgets = count($budgetRows);
        $compliantBudgets = 0;
        foreach ($budgetRows as $b) {
            if ((float) $b['terpakai'] <= (float) $b['batas_limit']) $compliantBudgets++;
        }
        $complianceRate = $totalBudgets > 0 ? round(($compliantBudgets / $totalBudgets) * 100) : 100;

        // WAJIB: catat setiap kali admin membuka ringkasan finansial seorang user
        writeLog($pdo, $_SESSION['name'] ?? 'Admin', "Membuka ringkasan finansial user \"{$targetUser['name']}\".");

        echo json_encode([
            'status' => 'success',
            'data' => [
                'name' => $targetUser['name'],
                'email' => $targetUser['email'],
                'total_bulan_ini' => $totalBulanIni,
                'needs_total' => $needs,
                'wants_total' => $wants,
                'kategori_terbesar' => $topCat ? $topCat['nama_kategori'] : null,
                'kategori_terbesar_total' => $topCat ? (float) $topCat['total'] : 0,
                'health_score' => $healthScore,
                'compliance_rate' => $complianceRate,
                'total_budgets' => $totalBudgets,
                'compliant_budgets' => $compliantBudgets
            ]
        ]);
        exit;
    }

    elseif ($action === 'dump_sql') {
        // Menghasilkan dynamic backup file SQL database
        $tables = ['tb_user', 'tb_buku_tabungan', 'tb_anggaran', 'tb_kategori', 'tb_transaksi', 'tb_system_logs'];
        
        $sqlDump = "-- SisaJejakUang Database Dump\n";
        $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sqlDump .= "-- Host: 127.0.0.1\n\n";
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Dapatkan struktur tabel
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $row = $stmt->fetch();
            $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
            $sqlDump .= $row['Create Table'] . ";\n\n";

            // Dapatkan data tabel
            $stmt = $pdo->query("SELECT * FROM `$table`");
            $rows = $stmt->fetchAll();

            if (count($rows) > 0) {
                $sqlDump .= "INSERT INTO `$table` (";
                
                // Ambil daftar nama kolom
                $columns = array_keys($rows[0]);
                $sqlDump .= implode(', ', array_map(function($c) { return "`$c`"; }, $columns));
                $sqlDump .= ") VALUES\n";

                $valArr = [];
                foreach ($rows as $r) {
                    $escapedVals = [];
                    foreach ($columns as $col) {
                        $val = $r[$col];
                        if ($val === null) {
                            $escapedVals[] = "NULL";
                        } else {
                            // Escape data
                            $escapedVals[] = $pdo->quote($val);
                        }
                    }
                    $valArr[] = "(" . implode(', ', $escapedVals) . ")";
                }
                $sqlDump .= implode(",\n", $valArr) . ";\n\n";
            }
        }
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Kembalikan file SQL sebagai unduhan attachment
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="dump_sisajejakuang_' . date('Ymd_His') . '.sql"');
        header('Content-Length: ' . strlen($sqlDump));
        
        echo $sqlDump;
        exit;
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenali.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses data admin: ' . $e->getMessage()]);
    exit;
}
