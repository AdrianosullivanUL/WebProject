<?php

/*
 * This file stores all shared php functions for the group05 project
 */

function update_match_status($db_connection, $match_id, $match_status_id, $updateUserStatus) {
    // TBD fire updates on the match tabel
    if ($updateUserStatus == 1) {
        $sql = "UPDATE  SET user_1_match_status_id = (select id from status_master where status_description ='"
                . $match_status_id . "' and is_match_table_status = true) where id = " . $match_id;
    } else {
        $sql = "UPDATE  SET user_2_match_status_id = (select id from status_master where status_description ='"
                . $match_status_id . "' and is_match_table_status = true) where id = " . $match_id;
    }
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
        error_log("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
}

function execute_sql_update($db_connection, $sql) {
    if ($db_connection->query($sql) === TRUE) {
        return $db_connection->affected_rows;
    } else {
        error_log("Error updating record: " . $db_connection->error);
        return false;
    }
}
