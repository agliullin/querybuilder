<?php

/**
 * QueryBuilder Class.
 *
 * @var string $Host хост
 * @var string $UserName имя пользователя
 * @var string $UserPassword пароль пользователя
 * @var string $DBName название базы данных
 * @var object $Connection соединение с сервером MySQL
 * @var array $Tables названия таблиц
 * @var integer $QueryType тип запроса
 * @var array $Into массив с названиями колонок для выполнения запроса INSERT
 * @var array $Values массив со значениями для выполнения запроса INSERT
 * @var array $SetColumns массив с названиями колонок для выполнения запроса UPDATE
 * @var array $SetValues массив со значениями для выполнения запроса UPDATE
 * @var array $OrderByColumns массив с названиями колонок для выполнения сортировки
 * @var array $OrderBySort массив с названиями сортировок (ASC, DESC) для выполнения сортировки
 * @var integer $Limit число для выполнения операции LIMIT
 * @var string $WhereMessage строка, хранящая условие WHERE
 * @var array $SelectColumns массив с названиями колонок для выполнения запроса SELECT
 * @var array $InnerTables массив с названиями таблиц для выполнения Inner Join
 * @var array $LeftTables массив с названиями таблиц для выполнения Left Join
 * @var array $RightTables массив с названиями таблиц для выполнения Right Join
 * @var array $FullTables массив с названиями таблиц для выполнения Full Join
 * @var string $OnColumn1 название колонки 1-й таблицы для выполнения операции ON
 * @var string $Action название действия между колонками таблиц для выполнения операции ON
 * @var string $OnColumn2 название колонки 2-й таблицы для выполнения операции ON
 */
class QueryBuilder { 
	private $Host = 'localhost'; 
	private $UserName = 'root'; 
	private $UserPassword = ''; 
	private $DBName = 'site'; 
	private $Connection = null;
	private $Tables = null;
	private $QueryType = null;
	private $Into = null;
	private $Values = null;
	private $SetColumns = null;
	private $SetValues = null;
	private $OrderByColumns = null;
	private $OrderBySort = null;
	private $Limit = null;
	private $WhereMessage = "";
	private $SelectColumns = null;
	private $InnerTables = null;
	private $LeftTables = null;
	private $RightTables = null;
	private $FullTables = null;
	private $OnColumn1 = null;
	private $OnAction = null;
	private $OnColumn2 = null;
	/**
	 * Конструктор.
	 */
	function __construct() {
		$Connection = new mysqli($this->Host, $this->UserName, $this->UserPassword, $this->DBName);
		if (!$Connection) { 
			echo $this->error(); 
			return FALSE; 
		}
		$this->Connection = $Connection;
	}
	/**
	 * Метод, который определяет тип запроса как INSERT.
	 */
	function InsertQuery() {
		$this->QueryType = 1;
	}
	/**
	 * Метод, который определяет тип запроса как UPDATE.
	 */
	function UpdateQuery() {
		$this->QueryType = 2;
	}
	/**
	 * Метод, который определяет тип запроса как DELETE.
	 */
	function DeleteQuery() {
		$this->QueryType = 3;
	}
	/**
	 * Метод, который определяет тип запроса как SELECT.
	 */
	function SelectQuery() {
		$this->QueryType = 4;
	}
	/**
	 * Метод, который принимает колонки для выполнения запроса INSERT.
	 * @param array $Columns массив с названиями колонок.
	 */
	function Into($Columns){
		$flag = true;
		foreach ($Columns as $value) {
			if (!is_string($value)) {
				$flag = false;
				break;
			}	
		}
		if ($this->QueryType == 1 && $flag == true) {
			$this->Into = $Columns;
		}
	}
	/**
	 * Метод, который принимает значения для выполнения запроса INSERT.
	 * @param array $Values массив с значениями.
	 */
	function Values($Values) {
		$flag = true;
		foreach ($Values as $value) {
			if (!is_string($value)) {
				$flag = false;
				break;
			}	
		}
		if ($this->QueryType == 1 && $flag == true) {
			$this->Values = $Values;
		}
	}
	/**
	 * Метод, который принимает названия колонок и значения для выполнения запроса UPDATE.
	 * @param array $Columns массив с названиями колонок.
	 * @param array $Values массив значений.
	 */
	function Set($Columns, $Values) {
		$flagc = true;
		foreach ($Columns as $value) {
			if (!is_string($value)) {
				$flagc = false;
				break;
			}	
		}
		$flagv = true;
		foreach ($Values as $value) {
			if (!is_string($value)) {
				$flagv = false;
				break;
			}	
		}
		if ($flagc == true && $flagv == true) {
			$this->SetColumns = $Columns;
			$this->SetValues = $Values;
		}
	}
	/**
	 * Метод, который принимает названия колонок для выполнения сортировки ORDER BY ASC (по умолчанию).
	 * @param array $Columns массив с названиями колонок.
	 */
	function OrderBy($Columns) {
		$flag = true;
		foreach ($Columns as $value) {
			if (!is_string($value)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->OrderByColumns = $Columns;
		}
	}
	/**
	 * Метод, который принимает названия колонок и виды сортировок для выполнения сортировки ORDER BY.
	 * @param array $Columns массив с названиями колонок.
	 * @param array $Sort массив с названиями сортировок (ASC, DESC).
	 */
	function OrderBySort($Columns, $Sort) {
		$flagc = true;
		foreach ($Columns as $value) {
			if (!is_string($value)) {
				$flagc = false;
				break;
			}	
		}
		$flags = true;
		foreach ($Sort as $value) {
			if (!is_string($value)) {
				$flags = false;
				break;
			}	
		}
		if ($flagc == true && $flags == true) {
			$this->OrderByColumns = $Columns;
			$this->OrderBySort = $Sort;
		}
	}
	/**
	 * Метод, который принимает число для выполнения операции LIMIT.
	 * @param integer $Num число для операции LIMIT.
	 */
	function Limit($Num) {
		if (is_int($Num)) {
			$this->Limit = $Num;
		}
	}
	/**
	 * Метод, который начинает приём условий для выполнения оператора WHERE.
	 */
	function WhereBegin() {
		$this->WhereMessage = "WHERE ";
	}
	/**
	 * Метод, который добавляет в сообщение оператора WHERE открывающуюся скобку '('.
	 */
	function WhereOpen() {
		if ($this->WhereMessage != "") {
			$this->WhereMessage .= "(";
		}
	}
	/**
	 * Метод, который добавляет в сообщение оператора WHERE закрывающуюся скобку ')'.
	 */
	function WhereClose() {
		if ($this->WhereMessage != "") {
			$this->WhereMessage .= ")";
		}
	}
	/**
	 * Метод, который добавляет в сообщение оператора WHERE оператор AND.
	 */
	function WhereAnd() {
		if ($this->WhereMessage != "") {
			$this->WhereMessage .= " AND ";
		}
	}
	/**
	 * Метод, который добавляет в сообщение оператора WHERE оператор OR.
	 */
	function WhereOr() {
		if ($this->WhereMessage != "") {
			$this->WhereMessage .= " OR ";
		}
	}
	/**
	 * Метод, который принимает колонку, действие и значение для выполнения условия (WHERE ...).
	 * @param string $Column название колонки.
	 * @param string $Action действие ("=", ">", "<" и т.д.).
	 * @param string $Value значение.
	 */
	function Where($Column,$Action,$Value) {
		if (is_string($Column) && is_string($Action) && is_string($Value)) {
			$this->WhereMessage .= $Column.$Action."".$Value;
		}
	}
	/**
	 * Метод, который принимает названия колонок для построения SELECT запроса.
	 * @param array $Columns названия колонок.
	 */
	function SelectColumns($Columns) {
		$flag = true;
		foreach ($Columns as $Column) {
			if (!is_string($Column)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->SelectColumns = $Columns;
		}
	}
	/**
	 * Метод, который принимает названия таблицы для выполнения дальнейших запросов.
	 * @param array $Tables массив с названиями таблиц.
	 */
	function From($Tables) {
		$flag = true;
		foreach ($Tables as $Table) {
			if (!is_string($Table)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->Tables = $Tables;
		}
	}
	/**
	 * Метод, который принимает названия таблицы для выполнения InnerJoin.
	 * @param array $Tables массив с названиями таблиц.
	 */
	function InnerJoin($Tables) {
		$flag = true;
		foreach ($Tables as $Table) {
			if (!is_string($Table)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->InnerTables = $Tables;
		}
	}
	/**
	 * Метод, который принимает названия таблицы для выполнения LeftJoin.
	 * @param array $Tables массив с названиями таблиц.
	 */
	function LeftJoin($Tables) {
		$flag = true;
		foreach ($Tables as $Table) {
			if (!is_string($Table)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->LeftTables = $Tables;
		}
	}
	/**
	 * Метод, который принимает названия таблицы для выполнения RightJoin.
	 * @param array $Tables массив с названиями таблиц.
	 */
	function RightJoin($Tables) {
		$flag = true;
		foreach ($Tables as $Table) {
			if (!is_string($Table)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->RightTables = $Tables;
		}
	}
	/**
	 * Метод, который принимает названия таблицы для выполнения FullJoin.
	 * @param array $Tables массив с названиями таблиц.
	 */
	function FullJoin($Tables) {
		$flag = true;
		foreach ($Tables as $Table) {
			if (!is_string($Table)) {
				$flag = false;
				break;
			}	
		}
		if ($flag == true) {
			$this->FullTables = $Tables;
		}
	}
	/**
	 * Метод, который принимает названия колонок и действия между ними для выполнения Join.
	 * @param string $OnColumn1 название колонки с первой таблицы.
	 * @param string $OnAction название действия.
	 * @param string $OnColumn2 название колонки со второй таблицы.
	 */
	function On($Column1,$Action,$Column2) {
		if (is_string($Column1) && is_string($Action) && is_string($Column2)) {
			$this->OnColumn1 = $Column1;
			$this->OnAction = $Action;
			$this->OnColumn2 = $Column2;
		}
	}
	/**
	 * Метод, который выполняет запрос на основе полученных ранне данных.
	 */
	function Execute() {
		$QueryType = $this->QueryType;
		if ($QueryType == 1) { /// INSERT
			$Connection = $this->Connection;
			if ($this->Tables != null && $this->Into != null && $this->Values != null) {
				$TableMessage = "";
				foreach ($this->Tables as $Table) {
					$TableMessage .= $Table.", ";	
				}
				$TableMessage = substr($TableMessage, 0, -2);
				$Into = $this->Into;
				$Values = $this->Values;
				$IntoMessage = "";
				$ValuesMessage = "";
				foreach ($Into as $Column) : {
					$IntoMessage .= $Column.", ";
				}
				endforeach;
				$IntoMessage = substr($IntoMessage, 0, -2);
				foreach ($Values as $Value) : {
					$ValuesMessage .= "'".$Value."', ";
				}
				endforeach;
				$ValuesMessage = substr($ValuesMessage, 0, -2);
				$SQL = "INSERT INTO ".$TableMessage." (".$IntoMessage.") VALUES (".$ValuesMessage.")";
				if ($Result = $Connection->query($SQL)) {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех<br><br>';
				} else {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача<br><br>';
				}
			}
		} else if ($QueryType == 2) { /// UPDATE
			$Connection = $this->Connection;
			if ($this->Tables != null) {
				$TableMessage = "";
				foreach ($this->Tables as $Table) {
					$TableMessage .= $Table.", ";	
				}
				$TableMessage = substr($TableMessage, 0, -2);
				$WhereMessage = $this->WhereMessage;
				$SetColumns = $this->SetColumns;
				$SetValues = $this->SetValues;
				$Set = array_combine($SetColumns, $SetValues);
				$SetMessage = "";
				foreach($Set as $SetColumn=>$SetValue) {
					$SetMessage .= $SetColumn." = '".$SetValue."' ,";   
				}
				$SetMessage = substr($SetMessage, 0, -2);
				$SQL = "UPDATE ".$TableMessage." SET ".$SetMessage." ".$WhereMessage;
				if ($Result = $Connection->query($SQL)) {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех<br><br>';
				} else {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача<br><br>';
				}
			}
		} else if ($QueryType == 3) { /// DELETE
			$Connection = $this->Connection;
			if ($this->Tables != null) {
				$TableMessage = "";
				foreach ($this->Tables as $Table) {
					$TableMessage .= $Table.", ";	
				}
				$TableMessage = substr($TableMessage, 0, -2);
				$WhereMessage = $this->WhereMessage;
				$SQL = "DELETE FROM ".$TableMessage." ".$WhereMessage;
				if ($Result = $Connection->query($SQL)) {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех<br><br>';
				} else {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача<br><br>';
				}
			}
		} else if ($QueryType == 4) { /// SELECT
			$Connection = $this->Connection;
			if ($this->Tables != null) {
				$TableMessage = "";
				foreach ($this->Tables as $Table) {
					$TableMessage .= $Table.", ";	
				}
				$TableMessage = substr($TableMessage, 0, -2);
				$WhereMessage = $this->WhereMessage;
				$OrderByMessage = "";
				if ($this->OrderByColumns != null && $this->OrderBySort != null) {
					$OrderByMessage = "ORDER BY ";
					$OrderByColumns = $this->OrderByColumns;
					$OrderBySort = $this->OrderBySort;
					$OrderBy = array_combine($OrderByColumns, $OrderBySort);
					foreach($OrderBy as $Column=>$Sort) {
						$OrderByMessage .= $Column." ".$Sort.", ";   
					}
					$OrderByMessage = substr($OrderByMessage, 0, -2);
				} else if ($this->OrderByColumns != null) {
					$OrderByMessage = "ORDER BY ";
					$OrderByColumns = $this->OrderByColumns;
					foreach($OrderByColumns as $Column) {
						$OrderByMessage .= $Column.", ";   
					}
					$OrderByMessage = substr($OrderByMessage, 0, -2);
				}
				$LimitMessage = "";
				if ($this->Limit != null) {
					$LimitMessage = "LIMIT ".$this->Limit;
				}	
				$SelectColumnsMessage = " *";
				if ($this->SelectColumns != null) {
					$SelectColumnsMessage = "";
					foreach ($this->SelectColumns as $Column) {
						$SelectColumnsMessage .= $Column.", ";
					}
					$SelectColumnsMessage = substr($SelectColumnsMessage, 0, -2);
				}
				$JoinMessage = "";
				if ($this->OnColumn1 != null && $this->OnAction != null && $this->OnColumn2 != null) {
					if ($this->InnerTables != null) {
						$JoinMessage = "Inner Join ";
						foreach ($this->InnerTables as $Table) {
							$JoinMessage .= $Table.", ";	
						}
						$JoinMessage = substr($JoinMessage, 0, -2);
						$JoinMessage .= " ON ".$this->OnColumn1.$this->OnAction.$this->OnColumn2;
					} else if ($this->LeftTables != null) {
						$JoinMessage = "Left Join ";
						foreach ($this->LeftTables as $Table) {
							$JoinMessage .= $Table.", ";	
						}
						$JoinMessage = substr($JoinMessage, 0, -2);
						$JoinMessage .= " ON ".$this->OnColumn1.$this->OnAction.$this->OnColumn2;
					} else if ($this->RightTables != null) {
						$JoinMessage = "Right Join ";
						foreach ($this->RightTables as $Table) {
							$JoinMessage .= $Table.", ";	
						}
						$JoinMessage = substr($JoinMessage, 0, -2);
						$JoinMessage .= " ON ".$this->OnColumn1.$this->OnAction.$this->OnColumn2;
					} else if ($this->FullTables != null) {
						$JoinMessage = "Full Join ";
						foreach ($this->FullTables as $Table) {
							$JoinMessage .= $Table.", ";	
						}
						$JoinMessage = substr($JoinMessage, 0, -2);
						$JoinMessage .= " ON ".$this->OnColumn1.$this->OnAction.$this->OnColumn2;
					}
				}
				$QueryForTable = "SELECT ".$SelectColumnsMessage." FROM ".$TableMessage;
				$SQL = "SELECT ".$SelectColumnsMessage." FROM ".$TableMessage." ".$JoinMessage." ".$WhereMessage." ".$OrderByMessage." ".$LimitMessage;
				if ($Result = $Connection->query($QueryForTable)) {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех<br>';
					$RowColumnNames = mysqli_fetch_assoc($Result);
					$ColumnNames = array_keys($RowColumnNames);
					echo '<table><tr>';
					$Iterator = 0;
					while($ColumnName = $ColumnNames[$Iterator++])
					{
						echo '<th>'.$ColumnName.'</th>';
					}
					echo '</tr>';
					$Res = $Connection->query($SQL);
					while($Rows = mysqli_fetch_assoc($Res))
					{
						echo '<tr>';
						foreach($Rows as $Row => $Value)
						{
							echo '<td>'.$Value.'</td>';
						}
						echo '</tr>';
					}
					echo '</table>';
					$Result->close();
				} else {
					echo '<b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача<br><br>';
				}
			}
		}
	}
}
?> 