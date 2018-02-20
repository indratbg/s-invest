<?php
class DAO 
{
	/**
	 * Executes the SQL statement.
	 * This method is meant only for executing non-query SQL statement.
	 * No result set will be returned.
	 * @param String $sql the Sql Sintax want to run
	 * @param array $params input parameters (name=>value) for the SQL execution. This is an alternative
	 * to {@link bindParam} and {@link bindValue}. If you have multiple input parameters, passing
	 * them in this way can improve the performance. Note that if you pass parameters in this way,
	 * you cannot bind parameters or values using {@link bindParam} or {@link bindValue}, and vice versa.
	 * binding methods and  the input parameters this way can improve the performance.
	 * @return integer number of rows affected by the execution.
	 * @throws CException execution failed
	 */
	public static function executeSql($sql,$params=array())
	{
		return Yii::app()->db->createCommand($sql)->execute($params);
	}
	
	/**
	* Executes the SQL statement and returns query result.
	* This method is for executing an SQL query that returns result set.
	* @param String $sql the Sql Sintax want to run
	* @param array $params input parameters (name=>value) for the SQL execution. This is an alternative
	* to {@link bindParam} and {@link bindValue}. If you have multiple input parameters, passing
	* them in this way can improve the performance. Note that if you pass parameters in this way,
	* you cannot bind parameters or values using {@link bindParam} or {@link bindValue}, and vice versa.
	* binding methods and  the input parameters this way can improve the performance.
	* @return CDbDataReader the reader object for fetching the query result
	* @throws CException execution failed
	*/
	public static function querySql($sql,$params=array())
	{
		return Yii::app()->db->createCommand($sql)->query($params);
	}
	
	/**
	* Executes the SQL statement and returns all rows.
	* @param String $sql the Sql Sintax want to run
	* @param array $params input parameters (name=>value) for the SQL execution. This is an alternative
	* to {@link bindParam} and {@link bindValue}. If you have multiple input parameters, passing
	* them in this way can improve the performance. Note that if you pass parameters in this way,
	* you cannot bind parameters or values using {@link bindParam} or {@link bindValue}, and vice versa.
	* binding methods and  the input parameters this way can improve the performance.
	* @param boolean $fetchAssociative whether each row should be returned as an associated array with
	* column names as the keys or the array keys are column indexes (0-based).
	* @return array all rows of the query result. Each array element is an array representing a row.
	* An empty array is returned if the query results in nothing.
	* @throws CException execution failed
	*/
	public static function queryAllSql($sql,$params=array(),$fetchAssociative=true)
	{
		return Yii::app()->db->createCommand($sql)->queryAll($fetchAssociative, $params);
	}
	
	/**
	* Executes the SQL statement and returns the first row of the result.
	* This is a convenient method of {@link query} when only the first row of data is needed.
	* @param String $sql the Sql Sintax want to run
	* @param array $params input parameters (name=>value) for the SQL execution. This is an alternative
	* to {@link bindParam} and {@link bindValue}. If you have multiple input parameters, passing
	* them in this way can improve the performance. Note that if you pass parameters in this way,
	* you cannot bind parameters or values using {@link bindParam} or {@link bindValue}, and vice versa.
	* binding methods and  the input parameters this way can improve the performance.
	* @param boolean $fetchAssociative whether the row should be returned as an associated array with
	* column names as the keys or the array keys are column indexes (0-based).
	* @return mixed the first row (in terms of an array) of the query result, false if no result.
	* @throws CException execution failed
	*/
	public static function queryRowSql($sql,$params=array(),$fetchAssociative=true)
	{
		return Yii::app()->db->createCommand($sql)->queryRow($fetchAssociative, $params);
	}
	
}