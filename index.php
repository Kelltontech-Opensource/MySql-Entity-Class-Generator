<?php
include("resources/class.database.php");
$database = new Database("drupal_main");
/*
*
*@Created By : Durgesh 
*@Description: This package can be used to generate classes that wrap rows of MySQL database tables
 providing an object oriented interface to manipulate the table row data.
*@date :22.09.2015
*
*/

if (@$_REQUEST["f"] == "") {
    ?>

    <font face="Arial" size="3"><b>
        PHP MYSQL  Generator Class
    </b>
    </font>

    <font face="Arial" size="2"><b>

        <form action="index.php" method="POST" name="FORMGEN">

            1) Select Table Name: 
            <br>

            <select name="tablename[]" multiple="multiple">

                <?php
                $database->OpenLink();
                $tablelist = mysql_list_tables($database->database, $database->link);
                while ($row = mysql_fetch_row($tablelist)) {
                    print "<option value=\"$row[0]\">$row[0]</option>";
                }
                ?>
            </select>

            <p>
                2) Type Class Name (ex. "test"): <br>
                <input type="text" name="classname" size="50" value="">
            <p>
                3) Type Name of Key Field:
                <br>
                <input type="text" name="keyname" value="" size="50">
                <br>
                <font size=1>
                (Name of key-field with type number with autoincrement!)
                </font>
            <p>


                <input type="submit" name="s1" value="Generate Class">
                <input type="hidden" name="f" value="formshowed">

        </form>

    </b>
    </font>
    <p>
        <font size="1" face="Arial">
        <a href="javascript:void();" target="_blank">
            this is a script from KelltonTech
        </a>
        </font>


        <?php
    } else {

        //echo "<pre>"; print_r($_POST); die;
        foreach($_POST['tablename'] as $key =>$val){
// fill parameters from form
       /* $table = $_REQUEST["tablename"];
        $class = $_REQUEST["classname"];
        $file_name = $_REQUEST["classname"];*/
         
        $table = $val;
        $class = $val;
        $file_name = $val;
            
        $key = $_REQUEST["keyname"];

        $dir = dirname(__FILE__);
        $actualfile = $file_name . ".class" . ".php";
        $filename = $dir . "/entities/" . $actualfile;

// if file exists, then delete it
        if (file_exists($filename)) {
            unlink($filename);
        }

// open file in insert mode
        $file = fopen($filename, "w+");
        $filedate = date("d.m.Y");

        // Exclude Fields 
        //Fields name : created_on,created,created_at
        $exFields = array("created_on", "created", "created_at");

        $c = "";

        $c = "<?php
   
/*
*
* -------------------------------------------------------
* CLASSNAME:        $class
* GENERATION DATE:  $filedate
* CLASS FILE:       $actualfile
* FOR MYSQL TABLE:  $table
* FOR MYSQL DB:     $database->database
* -------------------------------------------------------
*
*/

namespace tomahawk;

class $class
{ 
";

        $sql = "SHOW COLUMNS FROM $table;";
        $database->query($sql);
        $result = $database->result;


        while ($row = mysql_fetch_array($result)) {
            $col = $row['Field'];


            if (!in_array($col, $exFields)) {
                $c.= "
public $$col;   // (normal Attribute)";
            }

//"print "$col";
        } // endwhile

        $cdb = "$" . "database";
        $cdb2 = "database";


        $cthis = "$" . "this->";
        $thisdb = $cthis . $cdb2 . " = " . "new Database();";
        $c.="
    
";

        $c.="
    

";

        $sql = "$" . "sql = ";
        $id = "$" . "id";
        $thisdb = "$" . "this->" . "database";
        $thisdbquery = "$" . "this->" . "database->query($" . "sql" . ")";
        $result = "$" . "result = ";
        $row = "$" . "row";
        $result1 = "$" . "result";
        $res = "$" . "result = $" . "this->database->result;";

        $key_row = "$" . "val";

        $thisinstance = "$" . "this->CI = & " . "get_instance();";
        $dbfrom = "$" . "this->CI->db->from('" . $table . "');";
        $dbcond = "$" . "this->CI->db->where('" . $key . "',$key_row);";
        $dbquery = "$" . "query = " . "$" . "this->CI->db->get();";
        $dbrow = "$" . "val =  $" . "query->row();";
        $c.="
// **********************
// CONSTRUCTO / LOAD
// **********************

public function __construct($key_row=NULL)
{

    "; 

        $sql = "SHOW COLUMNS FROM $table;";
        $database->query($sql);
        $result = $database->result;
        while ($row = mysql_fetch_array($result)) {        //echo "<pre>"; print_r($row);
            $col = $row['Field'];
            if (!in_array($col, $exFields)) {
                if ($row['Field'] == "org_id") {
                    $cthis = "$" . "this->" . $col . " =  !isset($" . "val->" . $col . ") ? "." get_instance()->getOrgId()" ." : " . "$" . "val->" . $col;
               
                }else if (is_null($row['Default'])) {
                    $cthis = "$" . "this->" . $col . " =  !isset($" . "val->" . $col . ") ? NULL : " . "$" . "val->" . $col;
                 }else if ($row['Default'] != "" && $row['Default'] != "CURRENT_TIMESTAMP") {
                    $cthis = "$" . "this->" . $col . " =  !isset($" . "val->" . $col . ") ? '" . $row['Default'] . "' : " . "$" . "val->" . $col;
               }else if ($row['Default'] == "CURRENT_TIMESTAMP") {
                    $cthis = "$" . "this->" . $col . " =  !isset($" . "val->" . $col . ") ? date('Y-m-d H:i:s') : " . "$" . "val->" . $col;
                }
                else {
                    $cthis = "$" . "this->" . $col . " = $" . "val->" . $col;
                }
                $c.="
 $cthis;
         ";
            }
        }



        $c.="
}
";


// GETTER
        $database->query($sql);
        $result = $database->result;
        while ($row = mysql_fetch_array($result)) {
            $col = $row['Field'];
            $mname = "get" . $col . "()";
            $mthis = "$" . "this->" . $col;
            if (!in_array($col, $exFields)) {
                $c.="
public function $mname
{
    return $mthis;
}
";
            }
        }





        $c.="
// **********************
// SETTER METHODS
// **********************

";
// SETTER
        $database->query($sql);
        $result = $database->result;
        while ($row = mysql_fetch_array($result)) {
            $col = $row['Field'];
             if (is_null($row['Default'])) {
                  $val = "!isset($" . "val->" . $col . ") ? NULL : " . "$" . "val->" . $col;
             }else if ($row['Default'] != "") {
                $val = "!isset($" . "val->" . $col . ") ? '" . $row['Default'] . "' : " . "$" . "val->" . $col;
            } else {
                $val = "$" . "val->" . $col;
            }
            $mname = "set" . $col . "($" . "val)";
            $mthis = "$" . "this->" . $col . " = ";
            if (!in_array($col, $exFields)) {
                $c.="
public function $mname
{
    $mthis $val;
}
";
            }
        }



        $c.= "

} 

?>

";
        fwrite($file, $c);

        print "
<font face=\"Arial\" size=\"3\"><b>
PHP MYSQL Class Generator
</b>
<p>
<font face=\"Arial\" size=\"2\"><b>
Class '$class' successfully generated as file '$filename'!
<p>
<a href=\"javascript:history.back();\">
back
</a>

</b></font>

";
        ?>
    <p>
        <font size="1" face="Arial">
        <a href="http://www.voegeli.li" target="_blank">
            this is a script from www.voegeli.li
        </a>
        </font>





        <?php
        } // End loop
    } // endif
    ?>