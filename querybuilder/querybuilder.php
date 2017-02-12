<?php

/**
 * QueryBuilder Class.
 *
 * @var string $Host хост
 * @var string $UserName имя пользователя
 * @var string $UserPassword пароль пользователя
 * @var string $DBName название базы данных
 * @var object $Connection соединение с сервером MySQL
 * @var string $TableName название таблицы
 * @var integer $QueryType тип запроса
 * @var array $Into массив с названиями колонок для выполнения запроса INSERT
 * @var array $Values массив со значениями для выполнения запроса INSERT
 * @var array $SetColumns массив с названиями колонок для выполнения запроса UPDATE
 * @var array $SetValues массив со значениями для выполнения запроса UPDATE
 * @var array $WhereColumns массив с названиями колонок для выполнения операции условия
 * @var array$WhereActions массив с операциями для выполнения операции условия
 * @var array $WhereValues массив со значениями для выполнения операции условия
 * @var array $WhereStatement массив со значениями AND, OR для выполнения операции условия
 * @var integer $WhereIterator число условий
 * @var boolean $FirstWhere переменная для контроля первого условия
 * @var array $OrderByColumns массив с названиями колонок для выполнения сортировки
 * @var array $OrderBySort массив с названиями сортировок (ASC, DESC) для выполнения сортировки
 * @var integer $Limit число для выполнения операции LIMIT
 */
class QueryBuilder { 
	private $Host = 'localhost'; 
	private $UserName = 'root'; 
	private $UserPassword = ''; 
	private $DBName = 'site'; 
	private $Connection = null;
	private $TableName = null;
	private $QueryType = null;
	private $Into = null;
	private $Values = null;
	private $SetColumns = null;
	private $SetValues = null;
	private $WhereColumns = null;
	private $WhereActions = null;
	private $WhereValues = null;
	private $WhereStatement = null;
	private $WhereIterator = 0;
	private $FirstWhere = false;
	private $OrderByColumns = null;
	private $OrderBySort = null;
	private $Limit = null;
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
	 * Метод, который принимает колонку, действие и значение для выполнения условия (WHERE ...).
	 * @param string $Column название колонки.
	 * @param string $Action действие ("=", ">", "<" и т.д.).
	 * @param string $Value значение.
	 */
	function Where($Column,$Action,$Value) {
		$flag = true;
		if (is_string($Column) && is_string($Action) && is_string($Value)) {
			$this->FirstWhere = true;
			$this->WhereStatement[$this->WhereIterator] = "";
			$this->WhereColumns[$this->WhereIterator] = $Column;
			$this->WhereActions[$this->WhereIterator] = $Action;
			$this->WhereValues[$this->WhereIterator] = $Value;
			$this->WhereIterator++;
		}
	}
	/**
	 * Метод, который принимает колонку, действие и значение для выполнения условия (WHERE ... AND ...).
	 * @param string $Column название колонки.
	 * @param string $Action действие ("=", ">", "<" и т.д.).
	 * @param string $Value значение.
	 */
	function AndWhere($Column,$Action,$Value) {
		if (is_string($Column) && is_string($Action) && is_string($Value)) {
			$this->WhereStatement[$this->WhereIterator] = "AND";
			$this->WhereColumns[$this->WhereIterator] = $Column;
			$this->WhereActions[$this->WhereIterator] = $Action;
			$this->WhereValues[$this->WhereIterator] = $Value;
			$this->WhereIterator++;
		}
	}
	/**
	 * Метод, который принимает колонку, действие и значение для выполнения условия (WHERE ... OR ...).
	 * @param string $Column название колонки.
	 * @param string $Action действие ("=", ">", "<" и т.д.).
	 * @param string $Value значение.
	 */
	function OrWhere($Column,$Action,$Value) {
		if (is_string($Column) && is_string($Action) && is_string($Value)) {
			$this->WhereStatement[$this->WhereIterator] = "OR";
			$this->WhereColumns[$this->WhereIterator] = $Column;
			$this->WhereActions[$this->WhereIterator] = $Action;
			$this->WhereValues[$this->WhereIterator] = $Value;
			$this->WhereIterator++;
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
	 * Метод, который принимает названия таблицы для выолнения дальнейших запросов.
	 * @param string $TableName название таблицы.
	 */
	function Table($TableName) {
		if (is_string($TableName)) {
			$this->TableName = $TableName;
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
	 * Метод, который выполняет запрос на основе полученных ранне данных.
	 */
	function Execute() {
		$QueryType = $this->QueryType;
		if ($QueryType == 1) { /// INSERT
			$Connection = $this->Connection;
			if ($this->TableName != null && $this->Into != null && $this->Values != null) {
				$TableName = $this->TableName;
				$Into = $this->Into;
				$Values = $this->Values;
				$IntoMessage = "";
				$ValuesMessage = "";
				foreach ($Into as $Column) : {
					$IntoMessage .= "`".$Column."`, ";
				}
				endforeach;
				$IntoMessage = substr($IntoMessage, 0, -2);
				foreach ($Values as $Value) : {
					$ValuesMessage .= "'".$Value."', ";
				}
				endforeach;
				$ValuesMessage = substr($ValuesMessage, 0, -2);
				$SQL = "INSERT INTO `".$TableName."` (".$IntoMessage.") VALUES (".$ValuesMessage.")";
				if ($Result = $Connection->query($SQL)) {
					echo '<br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех';
				} else {
					echo '<br><br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача';
				}
			}
		} else if ($QueryType == 2) { /// UPDATE
			$Connection = $this->Connection;
			if ($this->TableName != null && $this->WhereColumns != null && $this->WhereValues != null && $this->WhereActions != null && $this->FirstWhere != false) {
				$TableName = $this->TableName;
				$WhereMessage = "WHERE ";
				$WhereMessage .= $this->WhereColumns[0]." ".$this->WhereActions[0]." ".$this->WhereValues[0]." ";
				for ($i = 1; $i < $this->WhereIterator; $i++) {
					$WhereMessage .= $this->WhereStatement[$i]." ".$this->WhereColumns[$i]." ".$this->WhereActions[$i]." ".$this->WhereValues[$i]." ";
				}
				$SetColumns = $this->SetColumns;
				$SetValues = $this->SetValues;
				$Set = array_combine($SetColumns, $SetValues);
				$SetMessage = "";
				foreach($Set as $SetColumn=>$SetValue) {
					$SetMessage .= "`".$SetColumn."` = '".$SetValue."' ,";   
				}
				$SetMessage = substr($SetMessage, 0, -2);
				$SQL = "UPDATE `".$TableName."` SET ".$SetMessage." ".$WhereMessage;
				if ($Result = $Connection->query($SQL)) {
					echo '<br><br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех';
				} else {
					echo '<br><br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача';
				}
			}
		} else if ($QueryType == 3) { /// DELETE
			$Connection = $this->Connection;
			if ($this->TableName != null && $this->WhereColumns != null && $this->WhereValues != null && $this->WhereActions != null && $this->FirstWhere != false) {
				$TableName = $this->TableName;
				$WhereMessage = "WHERE ";
				$WhereMessage .= $this->WhereColumns[0]." ".$this->WhereActions[0]." ".$this->WhereValues[0]." ";
				for ($i = 1; $i < $this->WhereIterator; $i++) {
					$WhereMessage .= $this->WhereStatement[$i]." ".$this->WhereColumns[$i]." ".$this->WhereActions[$i]." ".$this->WhereValues[$i]." ";
				}
				$SQL = "DELETE FROM `".$TableName."` ".$WhereMessage;
				if ($Result = $Connection->query($SQL)) {
					echo '<br><br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех';
				} else {
					echo '<br><br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача';
				}
			}
		} else if ($QueryType == 4) { /// SELECT
			$Connection = $this->Connection;
			if ($this->TableName != null) {
				$TableName = $this->TableName;
				$WhereMessage = "";
				if ($this->WhereColumns != null && $this->WhereValues != null && $this->WhereActions != null && $this->FirstWhere != false)
				{
					$WhereMessage = "WHERE ";
					$WhereMessage .= $this->WhereColumns[0]." ".$this->WhereActions[0]." ".$this->WhereValues[0]." ";
					for ($i = 1; $i < $this->WhereIterator; $i++) {
						$WhereMessage .= $this->WhereStatement[$i]." ".$this->WhereColumns[$i]." ".$this->WhereActions[$i]." ".$this->WhereValues[$i]." ";
					}
				}
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
				$QueryForTable = "SELECT * FROM `".$TableName."`";
				$SQL = "SELECT * FROM `".$TableName."` ".$WhereMessage." ".$OrderByMessage." ".$LimitMessage;
				if ($Result = $Connection->query($QueryForTable)) {
					echo '<br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Успех';
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
					echo '<br><b>Запрос:</b> '.$SQL.'<br><b>Результат:</b> Неудача';
				}
			}
		}
	}
}
?> 