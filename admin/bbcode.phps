<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: bbcode.php                                                 # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");

//
// BBCode Locked!
//
error_msg($lang['BBCode'], "Sorry BBCode is currently hard coded due to security problems. It may return in the future!");

if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "add") {
        if(isset($_POST['Submit'])) {
            $db->query("INSERT INTO `".$db_prefix."bbcode` VALUES('',
                                                  '".$_POST['Search']."',
                                                  '".$_POST['Replace']."',
                                                  '".($_POST['type'] == '1') ? "" : $_POST['begin_ext']."',
                                                  '".($_POST['type'] == '1') ? "" : $_POST['end_ext']."',
                                                  '".$_POST['Name']."',
                                                  '".$_POST['type']."')");
            info_box($lang['BBCode_Manager'], $lang['BBCode_Added_Msg'], "?module=Admin&act=bbcode");
        } else {
                $theme = new Theme("add_bbcode", "add_bbcode.tpl");
                $theme->replace_tags("add_bbcode", array(
                        "TITLE" => $lang['Add_BBCode'],
                        "NAME" => "",
                        "SEARCH" => "",
                        "REPLACE" => "",
                        "BEGIN_EXT" => "",
                        "END_EXT" => ""
                ));
                $type_options = array("Simple" => "1", "Complex 1" => "2", "Complex 2" => "3");
                foreach($type_options as $name => $value) {
                        $theme->insert_nest("add_bbcode", "type_options", array(
                                "TYPE.NAME" => $name,
                                "TYPE.VALUE" => $value,
                                "TYPE.SELECTED" => ""
                        ));
                        $theme->add_nest("add_bbcode");
                }
                $theme->output("add_bbcode");
       }
} else if($_GET['func'] == "edit") {
        if(isset($_POST['Submit'])) {
                $begin_ext = ($_POST['type'] == '1') ? "" : $_POST['begin_ext'];
                $end_ext =   ($_POST['type'] == '1') ? "" : $_POST['end_ext'];
                $db->query("UPDATE `".$db_prefix."bbcode` SET `name`='".$_POST['Name']."',
                             `search`='".$_POST['Search']."',
                             `replace`='".$_POST['Replace']."',
                             `begin_ext`='$begin_ext',
                             `end_ext`='$end_ext'
                             WHERE `id`='".$_GET['id']."'");
                info_box($lang['BBCode_Manager'], $lang['BBCode_Updated_Msg'], "?act=bbcode");
        } else {
                $sql = $db->query ("SELECT * FROM `".$db_prefix."bbcode` WHERE `bbcode_id`='$id'");
                if($result = $db->fetch_array ($sql)) {
                        $theme = new Theme("edit_bbcode", "edit_bbcode.tpl");
                        $theme->replace_tags("edit_bbcode", array(
                                "TITLE" => $lang['Edit_BBCode'],
                                "NAME" => $result['bbcode_name'],
                                "SEARCH" => $result['bbcode_search'],
                                "REPLACE" => $result['bbcode_replace'],
                                "BEGIN_EXT" => changehtml($result['bbcode_begin_ext']),
                                "END_EXT" => changehtml($result['bbcode_end_ext'])
                        ));
                        $type_options = array("Simple" => "1", "Complex 1" => "2", "Complex 2" => "3");
                        foreach($type_options as $name => $value) {
                                $theme->insert_nest("edit_bbcode", "type_options", array(
                                        "TYPE.NAME" => $name,
                                        "TYPE.VALUE" => $value,
                                        "TYPE.SELECTED" => ($result['bbcode_type'] == $value) ? "SELECTED" : ""
                                        ));
                                $theme->add_nest("edit_bbcode");
                        }
                        $theme->output("edit_bbcode");
                } else {
                        error_msg($lang['Error'], $lang['Invalid_BBCode_Id']);
                }
        }
} else if($_GET['func'] == "delete") {
        $db->query("DELETE FROM `".$db_prefix."bbcode` WHERE `bbcode_id`='$id'");
        info_box($lang['BBCode_Manager'], $lang['BBCode_Deleted_Msg'], "?module=Admin&act=smilies");
} else {

        $theme = new Theme("bbcode", "bbcode.tpl");
        $sql = $db->query ("SELECT * FROM `".$db_prefix."bbcode` WHERE `bbcode_type`='1'");
        while ($result = $db->fetch_array($sql)) {
                $theme->insert_nest("bbcode", "simple_bbcode_row", array(
                        "ID" => $result['bbcode_id'],
                        "NAME" => $result['bbcode_name'],
                        "SEARCH" => $result['bbcode_search'],
                        "REPLACE" => $result['bbcode_replace']
                ));
                $theme->add_nest("bbcode");
        }
        $sql = $db->query ("SELECT * FROM `".$db_prefix."bbcode` WHERE `bbcode_type`='2'");
        while ($result = $db->fetch_array($sql)) {
                $theme->insert_nest("bbcode", "complex1_bbcode_row", array(
                        "ID" => $result['bbcode_id'],
                        "NAME" => $result['bbcode_name'],
                        "SEARCH" => $result['bbcode_search'],
                        "REPLACE" => $result['bbcode_replace'],
                        "BEGIN_EXT" => $result['bbcode_begin_ext'],
                        "END_EXT" => $result['bbcode_end_ext']
                ));
                $theme->add_nest("bbcode");
        }
        $sql = $db->query ("SELECT * FROM `".$db_prefix."bbcode` WHERE `bbcode_type`='3'");
        while ($result = $db->fetch_array($sql)) {
                $theme->insert_nest("bbcode", "complex2_bbcode_row", array(
                        "ID" => $result['bbcode_id'],
                        "NAME" => $result['bbcode_name'],
                        "SEARCH" => $result['bbcode_search'],
                        "REPLACE" => $result['bbcode_replace'],
                        "BEGIN_EXT" => $result['bbcode_begin_ext'],
                        "END_EXT" => $result['bbcode_end_ext']
                ));
                $theme->add_nest("bbcode");
        }
        $theme->output("bbcode");
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
