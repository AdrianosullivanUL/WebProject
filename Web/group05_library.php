<?php

/*
 * This file stores all shared php functions for the group05 project
 */

function update_match_status($db_connection, $match_id, $match_status, $updateUser1or2) {
    // TBD fire updates on the match tabel
    if ($updateUser1or2 == 1) {
        $sql = "UPDATE match_table SET user_1_match_status_id = (select id from status_master where status_description ='"
                . $match_status . "' and is_match_table_status = true) where id = " . $match_id;
    } else {
        $sql = "UPDATE match_table SET user_2_match_status_id = (select id from status_master where status_description ='"
                . $match_status . "' and is_match_table_status = true) where id = " . $match_id;
    }
    echo $sql;
    return execute_sql_update($db_connection, $sql);
}

function update_user_status($user_id, $user_status_id) {
    
}

function execute_sql_query($db_connection, $sql) {
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            return $result;
//        mysqli_free_result($result);
        } else {
            return null;
        }
    } else {
        error_log("ERROR: Could not able to execute $sql. " . mysqli_error($db_connection));
    }
}

function execute_sql_update($db_connection, $sql) {
    if ($db_connection->query($sql) === TRUE) {
        return $db_connection->affected_rows;
    } else {
        echo "Error updating record: " . $db_connection->error;
        error_log("Error updating record: " . $db_connection->error);
        return false;
    }
}

function get_communications_thread($db_connection, $userId, $matchUserId) {
    $sql = "Select communication_id from match_table "
            ." where (match_user_id_1 = " . $userId . " and match_user_id_2 = " . $matchUserId . ")"
            ." or (match_user_id_1 = " . $matchUserId . " and match_user_id_2 = " . $userId . ")";
   // echo $sql . "<br>";
    $result = execute_sql_query($db_connection, $sql);
    if ($result == null)
        return null;
    while ($row = mysqli_fetch_array($result)) {
        $sql = "select uc.* from ("
                . "select " . $row['communication_id'] . " as id,0 as replying_to_id"
                . " union"
                . " select  id, replying_to_communication_id"
                . " from    (select * from user_communication"
                . "          order by replying_to_communication_id, id) comms_sorted,"
                . "        (select @pv := '" . $row['communication_id'] . "') initialisation"
                . " where   find_in_set(replying_to_communication_id, @pv)"
                . " and     length(@pv := concat(@pv, ',', id))) h"
                . " join user_communication uc on uc.id = h.id";
        echo $sql . "<br>";
            $result = execute_sql_query($db_connection, $sql);
            return $result;
            exit;
    }
}
    