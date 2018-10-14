<?php

$db = new mysqli("localhost", "host1521834", "1c379a6f", "host1521834_ekapie");


if ($_POST['query'] === "c") {
    get_call();
} elseif ($_POST['query'] === "f") {
    get_form();
}

if ($_GET['list'] === "calls")
    calls($_GET['start']);
if ($_GET['list'] === "forms")
    forms($_GET['start']);

function get_call() {
    global $db;
    $number = input_phone($_POST['number']);

    if (preg_match("/^[0-9]{7,14}$/", $number)) {
        $date = time();
        $db->query("INSERT INTO `host1521834_ekapie`.`calls` (`id`, `phone`, `date`) VALUES (NULL, '$number', '$date')");
        calls_mail();
        print "Ваша заявка принята!";
    } else {
        print "Неправильно задан номер телефона!";
    }
}

function get_form() {
    global $db;
    $name = input_text($_POST['name']);
    $number = input_phone($_POST['number']);
    $text = input_text($_POST['text']);
    $error = "";

    if ($name === "")
        $error .= "<p>Поле Имя обязательно!</p>";
    if (!preg_match("/^[0-9]{7,14}$/", $number))
        $error .= "<p>Неправильно задан номер телефона!</p>";
    if ($text === "")
        $error .= "<p>Поле Текст должно быть заполнено!</p>";

    if ($error === "") {
        $date = time();

        $q = $db->query("INSERT INTO `host1521834_ekapie`.`forms` (`id`, `name`, `phone`, `comments`, `date`) VALUES ('', '$name', '$number', '$text', '$date')");
        forms_mail();
        print "Ваше сообщение отправлено!";
    } else {
        print $error;
    }
}

function input_text($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function input_phone($number) {
    $arr = [
        " ",
        "-",
        "(",
        ")",
        "+"
    ];

    return str_replace($arr, "", $number);
}

function calls_mail() {
    $to = 'krasnuha@list.ru';
    $subject = 'Заявка ekapie.ru';
    $message = 'Вам пришла заявка! http://www.ekapie.ru/host.php';
    $headers = 'From: admin@ekapie.ru' . "\r\n" .
            'Reply-To: From: admin@ekapie.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

function forms_mail() {
    $to = 'krasnuha@list.ru';
    $subject = 'Сообщение ekapie.ru';
    $message = 'Вам пришло сообщение! http://www.ekapie.ru/host.php';
    $headers = 'From: admin@ekapie.ru' . "\r\n" .
            'Reply-To: From: admin@ekapie.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

function new_time($time = null) {
    $time = $time; // - (60 * 60); // msk

    $date = date('j.m.Y', $time);
    $date_exp = explode('.', $date);
    $date_time = date('H:i', $time);
    $date_year = date('Y', $time);

    $month = array(
        1 => 'янв',
        2 => 'фев',
        3 => 'мар',
        4 => 'апр',
        5 => 'мая',
        6 => 'июн',
        7 => 'июл',
        8 => 'авг',
        9 => 'сен',
        10 => 'окт',
        11 => 'ноя',
        12 => 'дек'
    );

    foreach ($month as $key => $value) {
        if ($key == intval($date_exp[1])) {
            $month_name = $value;
        }
    }

    if ($date == date('j.m.Y')) {
        return 'сегодня в ' . $date_time;
    } else if ($date == date('j.m.Y', strtotime('-1 day'))) {
        return 'вчера в ' . $date_time;
    } else {
        if ($date_year == date('Y')) {
            return $date_exp[0] . ' ' . $month_name . ' в ' . $date_time;
        } else {
            return $date_exp[0] . ' ' . $month_name . ' ' . $date_exp[2] . ' в ' . $date_time;
        }
    }
}

function calls($start) {
    global $db, $dbName;

    $items = array();
    $allItems = 0;
    $html = NULL;
    $limit = 10;
    $pageCount = 0;

    if ($_GET['start']) {
        if (filter_var($_GET['start'], FILTER_VALIDATE_INT) === false) {
            return "Неправильный запрос";
        }
    }

    if ($start === null) {
        $start = 0;
    }

    $sql = $db->query("SELECT id, phone, date FROM `host1521834_ekapie`.`calls` ORDER BY `id` DESC  LIMIT $start, $limit ");

    print "<h2>Заявки</h2>
        <table class=\"table table-striped\">
    <thead>
      <tr>
        <th>ID</th>
        <th>Phone</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>";
    while ($items = $sql->fetch_assoc()) {
        $date = new_time($items['date']);
        print "<tr>
        <td>{$items['id']}</td>
        <td>{$items['phone']}</td>
        <td>{$date}</td>
        </tr>";
    }
    print "</tbody>
  </table>";

    $sql_1 = "SELECT COUNT(*) FROM `host1521834_ekapie`.`calls`";
    $stmt = $db->query($sql_1);
    $row = mysqli_fetch_row($stmt);
    $allItems = $row[0];


    $pageCount = ceil($allItems / $limit);

    for ($i = 0; $i < $pageCount; $i++) {
        $html .= '<li><a href="/" onclick="calls(' . ($i * $limit) . '); return false;">' . ($i + 1) . '</a></li>';
    }

    echo '<ul class="pagination">' . $html . '</ul>';
}

function forms($start) {
    global $db, $dbName;

    $items = array();
    $allItems = 0;
    $html = NULL;
    $limit = 10;
    $pageCount = 0;

    if ($_GET['start']) {
        if (filter_var($_GET['start'], FILTER_VALIDATE_INT) === false) {
            return "Неправильный запрос";
        }
    }

    if ($start === null) {
        $start = 0;
    }

    $sql = $db->query("SELECT id, name, phone, comments, date FROM `host1521834_ekapie`.`forms` ORDER BY `id` DESC  LIMIT $start, $limit ");

    print "<h2>Сообщения</h2>
            <table class=\"table table-striped\">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Comments</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>";
    while ($items = $sql->fetch_assoc()) {
        $date = new_time($items['date']);
        print "<tr>
        <td>{$items['id']}</td>
        <td>{$items['name']}</td>
        <td>{$items['phone']}</td>
        <td>{$items['comments']}</td>
        <td>{$date}</td>
        </tr>";
    }
    print "</tbody>
  </table>";

    $sql_1 = "SELECT COUNT(*) FROM `host1521834_ekapie`.`forms`";
    $stmt = $db->query($sql_1);
    $row = mysqli_fetch_row($stmt);
    $allItems = $row[0];


    $pageCount = ceil($allItems / $limit);

    for ($i = 0; $i < $pageCount; $i++) {
        $html .= '<li><a href="/" onclick="form(' . ($i * $limit) . '); return false;">' . ($i + 1) . '</a></li>';
    }

    echo '<ul class="pagination">' . $html . '</ul>';
}