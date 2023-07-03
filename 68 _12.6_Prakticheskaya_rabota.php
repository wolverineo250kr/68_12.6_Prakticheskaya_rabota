<?php
$examplePersonsArray = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $patronymic) {
    return "$surname $name $patronymic";
}

function getPartsFromFullname($fullname) {
    $parts = explode(" ", $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronymic' => $parts[2]
    ];
}

function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $surname = $parts['surname'];
    $name = $parts['name'];
    $initial = mb_substr($parts['patronymic'], 0, 1, "UTF-8");

    return "$name $initial.";
}

function getGenderFromName($fullname)
{
    $parts = getPartsFromFullname($fullname); // Получаем составляющие ФИО
    $genderSign = 0; // Изначально суммарный признак пола равен 0

    // Проверяем наличие признаков мужского и женского пола и соответственно увеличиваем или уменьшаем суммарный признак пола
    if (isset($parts['patronymic']) && endsWith($parts['patronymic'], 'ич')) {
        $genderSign++;
    }
    if (isset($parts['name']) && (endsWith($parts['name'], 'й') || endsWith($parts['name'], 'н'))) {
        $genderSign++;
    }
    if (isset($parts['surname']) && endsWith($parts['surname'], 'в')) {
        $genderSign++;
    }
    if (isset($parts['patronymic']) && endsWith($parts['patronymic'], 'вна')) {
        $genderSign--;
    }
    if (isset($parts['surname']) && endsWith($parts['surname'], 'ва')) {
        $genderSign--;
    }
    if (isset($parts['name']) && endsWith($parts['name'], 'а')) {
        $genderSign--;
    }

    // Возвращаем результат в зависимости от значения суммарного признака пола
    if ($genderSign > 0) {
        return 1; // Мужской пол
    } elseif ($genderSign < 0) {
        return -1; // Женский пол
    } else {
        return 0; // Неопределенный пол
    }
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return substr($haystack, -$length) === $needle;
}

function getGenderDescription($persons) {
    $maleCount = 0;
    $femaleCount = 0;
    $unknownCount = 0;

    foreach ($persons as $person) {
        $gender = getGenderFromName($person['fullname']);
        if ($gender === 1) {
            $maleCount++;
        } elseif ($gender === -1) {
            $femaleCount++;
        } else {
            $unknownCount++;
        }
    }

    $total = count($persons);
    $malePercentage = round(($maleCount / $total) * 100, 1);
    $femalePercentage = round(($femaleCount / $total) * 100, 1);
    $unknownPercentage = round(($unknownCount / $total) * 100, 1);

    $genderDescription = "Гендерный состав аудитории:\n";
    $genderDescription .= "---------------------------\n";
    $genderDescription .= "Мужчины - $malePercentage%\n";
    $genderDescription .= "Женщины - $femalePercentage%\n";
    $genderDescription .= "Не удалось определить - $unknownPercentage%\n";

    return $genderDescription;
}

function getPerfectPartner($surname, $name, $patronymic, $persons) {
    $fullname = getFullnameFromParts($surname, $name, $patronymic);
    $gender = getGenderFromName($fullname);

    while (true) {
        $randomPerson = $persons[array_rand($persons)];
        $randomGender = getGenderFromName($randomPerson['fullname']);
        if ($gender !== $randomGender) {
            break;
        }
    }

    $percentage = mt_rand(5000, 10000) / 100;
    $result = "\n__________";
    $result .= getShortName($fullname) . ' + ' . getShortName($randomPerson['fullname']) . ' = ' . "\n";
    $result .= '♡ Идеально на ' . $percentage . '% ♡';
    $result .= "__________\n";
    
    return $result;
}

// Пример использования функций:

$fullname = getFullnameFromParts('Иванов', 'Иван', 'Иванович');
echo $fullname;  
 

$parts = getPartsFromFullname($examplePersonsArray[2]['fullname']);
print_r($parts);
echo "\n\n"; 
 
$shortName = getShortName($examplePersonsArray[4]['fullname']);
echo $shortName; 
echo "\n\n"; 

$gender = getGenderFromName($examplePersonsArray[8]['fullname']);
echo $gender; 
echo "\n\n"; 

$genderDescription = getGenderDescription($examplePersonsArray);
echo $genderDescription;
 

$perfectPartner = getPerfectPartner('Иванов', 'Иван', 'Иванович', $examplePersonsArray);
echo $perfectPartner;