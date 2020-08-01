<?php
$fp = fopen('log.txt', 'a');
$users = [
    'test@test.ru' => [
        'first_name' => 'Антон',
        'last_name' => 'Данилкин',
        'password' => 'test'
    ],
    'test2@test.ru' => [
        'first_name' => 'Иван',
        'last_name' => 'Иванов',
        'password' => 'test2'
    ]
];

if (isset($_POST['act']) && $_POST['act'] == 'registration') {
    $errors = [];
    $requiredFields = [
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'password' => 'Пароль',
        'password_repeat' => 'Повтор пароля',
        'email' => 'Email',
    ];

    foreach ($requiredFields as $k => $v) {
        if ($k != 'password' && $k != 'password_repeat')
            $_POST[$k] = trim($_POST[$k]);
        if (empty($_POST[$k]))
            $errors[$k] = 'Поле ' . $v . ' обязательно для заполнения';
        $v = $_POST[$k];
        switch ($k) {
            case 'email':
                if (!filter_var($v, FILTER_VALIDATE_EMAIL))
                    $errors[$k] = 'Неверный формат Email';
                if (isset($users[$v]))
                    $errors[$k] = 'Пользователь с таким Email уже существует';

                break;
            case 'password':
                if (strlen($v) < 6)
                    $errors[$k] = 'Минимальная длина пароля 6 символов';
                break;
            case 'password_repeat':
                if ($_POST['password'] != $_POST['password_repeat'])
                    $errors[$k] = 'Неверный повтор пароля';

                break;
        }
    }

    if ($errors) {
        $error = implode('<br>', $errors);
        fwrite($fp, $_POST['email'] . '||' . $error . PHP_EOL);
        exit(json_encode(['error' => $error]));
    }

    $users[$_POST['email']] = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'password' => $_POST['password']
    ];
    fwrite($fp, $_POST['email'] . '||success' . PHP_EOL);
    exit(json_encode(['success' => 'Регистрация прошла успешно']));

}