<?php
$con =mysql_connect("localhost","root","");
mysql_select_db("nokia");
function open($save_path, $session_name)
{
    return(true);
}
function close()
{
  return(true);
}
function read($id)
{
   if($result = mysql_query("SELECT * FROM npp_session WHERE id='".$id."'",$con))
     {
        if($row = mysql_felth_row($result ))
           {  return $row["data"]; }
      }
   else
     {
      return "";
      }
}
function write($id, $sess_data)
{
  if($result = mysql_query("UPDATE npp_session SET data='".$sess_data."' WHERE id='".$id."'",$con))
     {
        return true;
      }
   else
     {
      return false;
      }
}
function destroy($id)
{
 if($result = mysql_query("DELETE * FROM  npp_session WHERE id='".$id."'",$con))
     {
        return true;
      }
   else
     {
      return false;
      }
}
/*********************************************
* WARNING - You will need to implement some *
* sort of garbage collection routine here.  *
*********************************************/
function gc($maxlifetime)
{
  return true;
}
session_set_save_handler("open", "close", "read", "write", "destroy", "gc");
session_start();
// proceed to use sessions normally
?>