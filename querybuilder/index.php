<?php
include 'querybuilder.php';

$QB = new QueryBuilder(); 
$QB->SelectQuery();
$QB->Table('chatlist');
$Columns = ["chat_owner", "chat_name"];
$Sort = ["ASC","ASC"];
$QB->OrderBySort($Columns, $Sort);
$QB->Limit(2);
$QB->Execute();

$QB1 = new QueryBuilder(); 
$into = ["chat_name","chat_desc","chat_owner"];
$values = ["123","123","1"];
$QB1->InsertQuery();
$QB1->Into($into);
$QB1->Values($values);
$QB1->Table('chatlist');
$QB1->Execute();

$QB2 = new QueryBuilder(); 
$QB2->UpdateQuery();
$QB2->Table('chatlist');
$SetColumns = ["chat_desc"];
$SetValues = ["AAA"];
$QB2->Set($SetColumns, $SetValues);
$QB2->Where('chat_id','=','4');
$QB2->Execute();

$QB3 = new QueryBuilder();
$QB3->DeleteQuery();
$QB3->Table('chatlist');
$QB3->Where('chat_id','=','3');
$QB3->Execute();

?>