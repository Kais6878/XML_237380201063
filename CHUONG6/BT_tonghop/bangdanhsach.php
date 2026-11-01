
<?php
$xmlFile = __DIR__ . '/qlsv.xml';
$students = [];

if (file_exists($xmlFile)) {
    $dom = new DOMDocument();
    $dom->load($xmlFile);

    foreach ($dom->getElementsByTagName('student') as $student) {
        $getValue = function ($tag) use ($student) {
            $node = $student->getElementsByTagName($tag)->item(0);
            return $node ? $node->nodeValue : '';
        };

        $students[] = [
            'id' => $getValue('id'),
            'name' => $getValue('name'),
            'birthday' => $getValue('birthday'),
            'city' => $getValue('city')
        ];
    }
}

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<style>
    .student-table-wrap {
        width: 100%;
        overflow-x: auto;
        margin: 20px 0;
        font-family: Arial, sans-serif;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .student-table th,
    .student-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .student-table th {
        background: #0879d1;
        color: white;
    }

    .student-table tbody tr:nth-child(even) {
        background: #f5f7fa;
    }

    .student-table a {
        color: #0879d1;
        text-decoration: none;
    }

    .student-table a:hover {
        text-decoration: underline;
    }
</style>

<div class="student-table-wrap">
    <h3>Danh sách sinh viên</h3>

    <table class="student-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Thành phố</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $i => $s): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($s['id']) ?></td>
                        <td><?= e($s['name']) ?></td>
                        <td><?= e($s['birthday']) ?></td>
                        <td><?= e($s['city']) ?></td>
                        <td>
                            <a href="capnhat.php?id=<?= urlencode($s['id']) ?>">
                                Sửa thông tin
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Chưa có dữ liệu sinh viên.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
