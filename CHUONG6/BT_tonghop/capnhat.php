
<?php
$xmlFile = 'qlsv.xml';
$message = '';
$messageColor = 'blue';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $targetId = trim($_POST['id'] ?? '');
    $newName = trim($_POST['name'] ?? '');
    $newBirthday = trim($_POST['birthday'] ?? '');
    $newCity = trim($_POST['city'] ?? '');

    if (file_exists($xmlFile)) {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        if ($dom->load($xmlFile)) {
            $updated = false;

            foreach ($dom->getElementsByTagName('student') as $student) {
                $idNode = $student->getElementsByTagName('id')->item(0);

                if ($idNode && trim($idNode->nodeValue) === $targetId) {
                    $fields = [
                        'name' => $newName,
                        'birthday' => $newBirthday,
                        'city' => $newCity
                    ];

                    foreach ($fields as $tag => $value) {
                        $node = $student->getElementsByTagName($tag)->item(0);

                        if ($node) {
                            $node->nodeValue = $value;
                        } else {
                            $newNode = $dom->createElement($tag);
                            $newNode->appendChild(
                                $dom->createTextNode($value)
                            );
                            $student->appendChild($newNode);
                        }
                    }

                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                if ($dom->save($xmlFile) !== false) {
                    $message = 'Cập nhật sinh viên thành công!';
                    $messageColor = 'green';
                } else {
                    $message = 'Không thể lưu file XML!';
                    $messageColor = 'red';
                }
            } else {
                $message = 'Không tìm thấy sinh viên có ID: '
                    . htmlspecialchars($targetId, ENT_QUOTES, 'UTF-8');
                $messageColor = 'red';
            }
        } else {
            $message = 'Không thể đọc file XML!';
            $messageColor = 'red';
        }
    } else {
        $message = 'Không tìm thấy file qlsv.xml!';
        $messageColor = 'red';
    }
}

// Đọc danh sách sinh viên để hiển thị tham chiếu
$students = [];

if (file_exists($xmlFile)) {
    $listDom = new DOMDocument();

    if ($listDom->load($xmlFile)) {
        foreach ($listDom->getElementsByTagName('student') as $student) {
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
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cập nhật sinh viên</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }

        input {
            box-sizing: border-box;
            width: 320px;
            max-width: 100%;
            padding: 9px;
            margin-top: 6px;
        }

        button {
            padding: 9px 18px;
            border: none;
            border-radius: 4px;
            background: #0879d1;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #065fa5;
        }

        .message {
            font-weight: bold;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #0879d1;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f6fa;
        }

        .edit-btn {
            background: #198754;
            padding: 6px 12px;
        }

        .edit-btn:hover {
            background: #146c43;
        }

        .form-section {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <a href="trangchu.php">&larr; Quay lại trang chủ</a>

    <h2>Cập nhật thông tin sinh viên</h2>

    <div class="form-section">
        <form method="POST">
            <label for="id">ID sinh viên cần sửa:</label><br>
            <input type="text" id="id" name="id" required>
            <br><br>

            <label for="name">Họ tên mới:</label><br>
            <input type="text" id="name" name="name" required>
            <br><br>

            <label for="birthday">Ngày sinh:</label><br>
            <input type="date" id="birthday" name="birthday" required>
            <br><br>

            <label for="city">Thành phố:</label><br>
            <input type="text" id="city" name="city" required>
            <br><br>

            <button type="submit">Cập nhật</button>
        </form>

        <?php if ($message !== ''): ?>
            <p class="message" style="color: <?= $messageColor ?>;">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <hr>

    <h3>Danh sách sinh viên hiện có</h3>
    <p>Nhấn nút "Chọn sửa" ở dòng sinh viên để đưa thông tin lên biểu mẫu.</p>

    <div class="table-container">
        <table>
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
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($student['id'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['birthday'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['city'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="edit-btn"
                                    onclick='selectStudent(
                                        <?= json_encode(
                                            $student,
                                            JSON_HEX_TAG | JSON_HEX_APOS |
                                            JSON_HEX_QUOT | JSON_HEX_AMP
                                        ) ?>
                                    )'>
                                    Chọn sửa
                                </button>
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

    <script>
        function selectStudent(student) {
            document.getElementById('id').value = student.id;
            document.getElementById('name').value = student.name;
            document.getElementById('birthday').value =
                student.birthday.length >= 10
                    ? student.birthday.substring(0, 10)
                    : student.birthday;
            document.getElementById('city').value = student.city;

            document.getElementById('name').focus();

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
