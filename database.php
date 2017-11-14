<?php
//Create
function DBCreate($table, array $data, $ReturnId = false){
    $table  = $table;
    $data   = DBEscape($data);

    $fields = implode(',', array_keys($data));
    $values = "'".implode("', '", $data)."'";

    $query = "INSERT INTO {$table} ( {$fields} ) VALUES ( {$values})";
    return DBExecute($query, $ReturnId);
}

//Read
function DBRead($table,  $params = null, $fields = "*"){
    $table  = DB_PREFIX . '_' . $table;
    $params = ($params) ? " {$params}" : null;

    $query  = "SELECT {$fields} FROM {$table}{$params}";
    $result = DBExecute($query);

    if(!mysqli_num_rows($result))
        return false;
    else{
        while ($rs = mysqli_fetch_assoc($result)){
            $data[] =$rs;
        }
        return $data;
    }
}

//Update
function DBUpdate($table, array $data, $where = null, $ReturnId = false){
    foreach ($data as $key => $value){
        $fields[] = "{$key} = '{$value}'";
    }
    $fields = implode(', ',$fields);
    $table  = DB_PREFIX . '_' . $table;
    $where = ($where) ? " WHERE {$where}" : null;
    $query  = "UPDATE {$table} SET {$fields}{$where}";

    return DBExecute($query, $ReturnId);
}

//Inject
function DBExecute($query, $ReturnId = false){
    $link   = DBConnect();
    $result = @mysqli_query($link,$query) or die(mysqli_error($link));

    if($ReturnId){
        $result = mysqli_insert_id($link);
    }

    DBClose($link);
    return $result;
}

//Delete
function DBDelete($table, $where = null){
    $table  = DB_PREFIX . '_' . $table;
    $where = ($where) ? " WHERE {$where}": null;
    $query = "DELETE FROM {$table}{$where}";

    return DBExecute($query);
}?>