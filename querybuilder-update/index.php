<?php
include 'querybuilder.php';

$TableFrom = ["table1"];
$JoinTable = ["table2"];
$SelectColumns = ["data1", "data2"];

// Реализация Inner Join
$QueryBuilder1 = new QueryBuilder(); 
$QueryBuilder1->SelectQuery();
$QueryBuilder1->From($TableFrom);
$QueryBuilder1->SelectColumns($SelectColumns);
$QueryBuilder1->InnerJoin($JoinTable);
$QueryBuilder1->On("table1.key1","=","table2.key2");
$QueryBuilder1->Execute();

// Реализация Left Join
$QueryBuilder2 = new QueryBuilder(); 
$QueryBuilder2->SelectQuery();
$QueryBuilder2->From($TableFrom);
$QueryBuilder2->SelectColumns($SelectColumns);
$QueryBuilder2->LeftJoin($JoinTable);
$QueryBuilder2->On("table1.key1","=","table2.key2");
$QueryBuilder2->Execute();

// Реализация Right Join
$QueryBuilder3 = new QueryBuilder(); 
$QueryBuilder3->SelectQuery();
$QueryBuilder3->From($TableFrom);
$QueryBuilder3->SelectColumns($SelectColumns);
$QueryBuilder3->RightJoin($JoinTable);
$QueryBuilder3->On("table1.key1","=","table2.key2");
$QueryBuilder3->Execute();

// Реализация Full Join
$QueryBuilder4 = new QueryBuilder(); 
$QueryBuilder4->SelectQuery();
$QueryBuilder4->From($TableFrom);
$QueryBuilder4->SelectColumns($SelectColumns);
$QueryBuilder4->FullJoin($JoinTable);
$QueryBuilder4->On("table1.key1","=","table2.key2");
$QueryBuilder4->Execute();

// Реализация Select
$QB = new QueryBuilder(); 
$QB->SelectQuery();
$Tables = ["table1"];
$QB->From($Tables);
$SelectColumns = ["id", "name","desc"];
$QB->SelectColumns($SelectColumns);
$Columns = ["id", "name"];
$Sort = ["ASC","ASC"];
$QB->OrderBySort($Columns, $Sort);
$QB->WhereBegin();
$QB->WhereOpen();
$QB->Where("owner","=","1");
$QB->WhereAnd();
$QB->Where("name","=","'ABC'");
$QB->WhereClose();
$QB->Limit(10);
$QB->Execute();

// Реализация Insert
$QB1 = new QueryBuilder(); 
$into = ["name","desc","owner"];
$values = ["ABC","abc","1"];
$QB1->InsertQuery();
$QB1->Into($into);
$QB1->Values($values);
$QB1->From($Tables);
$QB1->Execute();

// Реализация Update
$QB2 = new QueryBuilder(); 
$QB2->UpdateQuery();
$QB2->From($Tables);
$SetColumns = ["table1"];
$SetValues = ["abc"];
$QB2->Set($SetColumns, $SetValues);
$QB2->WhereBegin();
$QB2->Where("id","=","4");
$QB2->Execute();

// Реализация Delete
$QB3 = new QueryBuilder();
$QB3->DeleteQuery();
$QB3->From($Tables);
$QB3->WhereBegin();
$QB3->Where("id","=","3");
$QB3->Execute();
?>