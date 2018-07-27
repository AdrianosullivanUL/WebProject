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
    // echo $sql;
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
            . " where (match_user_id_1 = " . $userId . " and match_user_id_2 = " . $matchUserId . ")"
            . " or (match_user_id_1 = " . $matchUserId . " and match_user_id_2 = " . $userId . ")";
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
        // echo $sql . "<br>";
        $result = execute_sql_query($db_connection, $sql);
        return $result;
        exit;
    }
}

function get_bad_words_in_text($db_connection, $checkText) {
// Get the words contained in the text provided
//   array str_split ( string $checkText [, int $split_length = 1 ] );
    $words = str_word_count($checkText, 1);
    $badWords = [];

    $firstWord = true;
    $sql = "select * from black_list_word where word in (";
    foreach ($words as &$word) {
        if ($firstWord) {
            $sql = $sql . "'" . md5($word) . "'";
            $firstWord = false;
        } else {
            $sql = $sql . ",'" . md5($word) . "'";
        }
    }
    $sql = $sql . ");";
    $result = execute_sql_query($db_connection, $sql);
    if ($result == null)
        return null;
    while ($row = mysqli_fetch_array($result)) {
        foreach ($words as &$word) {
            if (md5($word) == $row['word']) {
                $badWords[] = $word;
                break;
            }
        }
    }
    return $badWords;
}

function get_GUID() {
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = chr(123)// "{"
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125); // "}"
        return $uuid;
    }
}

function validate_logon($db_connection, $user_id, $session_hash) {
    $sql = "select count(*) cnt from user_profile where id = " . $user_id . " and session_hash = '" . $session_hash . "';";
    //echo $sql;
    $result = execute_sql_query($db_connection, $sql);
    if ($result != null) {
        while ($row = mysqli_fetch_array($result)) {
            if ($row['cnt'] == 1)
                return true;
        }
    }
    return false;
}
