<?php

$dbName = "";
$user = "";
$pass = "";

$db = new \PDO('mysql:dbname=' . $dbName, $user, $pass);

function fixName($name) {
    return preg_replace_callback("!(?:^(?=[a-z])|_)([a-z]+)!", function($x) { return ucwords($x[1]); }, $name);
}

function fixPath($name) {
    $name = explode("/", $name);
    foreach($name as $i => $piece) {
        $name[$i] = ucwords($piece);
    }
    return join("/", $name);
}

$s = $db->query("SELECT name FROM modules");
$modules = array();
foreach($s->fetchAll() as $row) {
    $newName = fixName($row['name']);
    $origName = $row['name'];
    
    $modules[] = $origName;
    
    echo "UPDATE modules_settings SET module='$newName' WHERE module='{$origName}';\n";
    echo "UPDATE locale SET module='$newName' WHERE module='{$origName}';\n";
    echo "UPDATE search_index SET module='$newName' WHERE module='{$origName}';\n";

    $e = $db->query("SELECT action FROM modules_extras WHERE module='{$origName}' AND action != '' AND action IS NOT NULL");
    foreach($e->fetchAll() as $extra) {
        $origActionName = $extra['action'];
        $newActionName = fixName($origActionName);
        echo "UPDATE modules_extras SET action='{$newActionName}' WHERE module='{$origName}' AND action='{$origActionName}';\n";
        echo "UPDATE groups_rights_actions SET module='{$newName}',action='{$newActionName}' WHERE module='{$origName}' AND action='{$origActionName}';\n";
        echo "UPDATE groups_rights_modules SET module='{$newName}' WHERE module='{$origName}';\n";
    }

    echo "UPDATE modules_extras SET module='$newName' WHERE module='{$origName}';\n";

}

$s = $db->query("SELECT path FROM themes_templates");
foreach($s->fetchAll() as $row) {
    $newPath = fixPath($row['path']);
    $origPath = $row['path'];
    echo "UPDATE themes_templates SET path='$newPath' WHERE path='{$origPath}';\n";
}


foreach($modules as $name) {
    $newName = fixName($name);
    echo "UPDATE modules SET name='$newName' WHERE name='$name';\n";
}

echo "UPDATE modules_settings SET value='a:1:{i:0;s:2:\"en\";}' WHERE module = 'Core' and name='active_languages' AND (value IS NULL OR value = 'N;');\n";
