<?php
///////////////////////////////////////////////////////////////////
// File: data.php
// Author: Sebatian Lenczewski
// Copyright: 2013, Sebastian Lenczewski, Algonquin College
// Desc: This file contains functions that help create, save and
// 			restore map arrays.  Save and restore from MySQL and 
//			SQLite and export the map data to an XML file.
///////////////////////////////////////////////////////////////////


	// Make a function that creates and returns a new blank map array
	function makeMapArray($width, $height, $tile_id)
	{
		// Make an array that will hold the map array data to be returned.
		$map = array();
		// Loop through the height and width as array positions
		for($y=0;$y<$height;$y++)
		{
			for($x=0;$x<$width;$x++)
			{
				// Set the value at this position to $tile_id
				$map[$y][$x]=$tile_id;
			}
		}
		
		//Return the array
		return $map;
	}
	
	
	
	// Make a function that saves the map array $tileMapArray to an SQLite
	// table with the name as $tableName
	function saveMapArray($tableName, $tileMapArray)
	{
		//1.
		// Open a PDO connection to the SQLite file called final.sqlite3
		$dbFile = new PDO('sqlite:final.sqlite3');
		// Check if the table exists by doing a select on the SQLite 
		// table called 'sqlite_master' to check if the table name 
		// exists.  Remember to fetch the data out of the results
		$myquery = $dbFile->query("SELECT count(name) FROM sqlite_master WHERE type='table' AND name='".$tableName."'");
		$countArray = $myquery->fetch(PDO::FETCH_ASSOC);
		
		//echo $countArray['count(name)']; //output: 1

		// If the results are 0 the table does not exist, and you must 
		// create the SQLite table.
		if ($countArray['count(name)'] == 0)
		{
			$dbFile->exec("CREATE TABLE ".$tableName."(
							position_id INTEGER PRIMARY KEY,
							position_row INTEGER,
							position_col INTEGER,
							tile_id INTEGER
							);");
		}
		// Else if it does exist you must empty the SQLite table. (do not drop table)
		else
		{
			$dbFile->exec("DELETE FROM ".$tableName);
			$dbFile->exec("VACUUM");

			$dbFile->exec("INSERT INTO ".$tableName."(`position_id`) VALUES (`1`);");
			$dbFile->exec("DELETE FROM ".$tableName." WHERE position_id = 1;");
			
		}
		// Generate one single SQLite query to insert all the $tileMapArray values to SQLite table 
		// by looping through the array called $tileMapArray. (Do some research on this.  
		// You need to use a SELECT and UNION to insert many records at once in SQLite)
		
		$query = "";
		
		for($y=0;$y<count($tileMapArray);$y++)
		{
			for($x=0;$x<count($tileMapArray[$y]);$x++)
			{
				if($x == 0 && $y == 0)
				{
					$query .= "INSERT INTO `".$tableName."` SELECT NULL AS `position_id`, $y AS `position_row`, $x AS `position_col`, ".$tileMapArray[$y][$x]." AS `tile_id`";
				}
				else
				{
					$query .= " UNION SELECT NULL, " .$y. ", " . $x . ", ".$tileMapArray[$y][$x];
				}
			}
		}

	//echo $query;
	// Excute the query to insert the array data
	$dbFile->exec($query);

	// Close connection to PDO object.
	$dbFile = null;
	}
	
	
	
	// Make a function that loads data from the specified SQLite 
	// table as an array, and returns the array back to the application
	function loadMapArray($tableName)
	{
		
		//Make an empty array that will hold the map array data to be returned.
		$map = array();
		
		//2.
		// Check if the file 'final.sqlite3' exists on the server
		if('final.sqlite3')
		{
			echo "The table exists<br>";
			// If the db file exists, open a link to it
			$dbFile = new PDO('sqlite:final.sqlite3');
			// Run a select query to return the whole table
			$query = $dbFile->query("SElECT * FROM $tableName");
			$result = $query->fetch(PDO::FETCH_ASSOC);
			//echo $result;
			foreach($result as $row)
			{
				echo "position_id: ".$row[0]."<br>";
				//echo "position_row: ".$row['position_row']."<br>";
				//echo "position_col: ".$row['position_col']."<br>";
				//echo "tile_id: ".$row['tile_id']."<br>";
			}
			echo "findings";
		}
			// If the results are not empty, set the given array position to the value 'tile_id'.
			// Remember that each row in the table has the 'position_row' and 'position_col' 
			// stored telling you what array position to fill.
			
			// Else, if the reults are empty, set the $map array equal to the return 
			// of the function makeMapArray(10,10,0)
		else
		{
			for($y=0;$y<10;$y++)
			{
				for($x=0;$x<10;$x++)
				{
					$map[$y][$x]=$tile_id;
				}
			}
		
		}
			
			
	// Close link to database
	$dbFile = null;

		// Else, if the SQLite file does not exist, set the $map array equal to the return
		// of the function makeMapArray(10,10,0) 

		
		// Return the $map array
		return $map;
		
	}
	
	
	
	// Create a function that takes map array data and inserts it into a 
	// given MySQL table in a database called final
	function uploadMapArray($tableName, $tileMapArray)
	{
		//3.
		// Connect to the database by creating a new PDO object
		
		// Create a table IF NOT EXISTS for the given $tableName
		
		// Run a truncate query on the table to remove any data
		
		// Loop through the the $tileMapArray array to generate a single query to 
		// insert all the records from the $tileMapArray into the MySQL table.
		
		// Exicute the insert query on the MySQL table
		
		// Close the PDO link to the database
		
	}
	
	
	
	// Create a function that selects the map data from the MySQL table 
	// and returns it as an array to the application.
	function downloadMapArray($tableName)
	{
		//4.
		//Make an empty array that will hold the map array data to be returned.
		$map = array();
		
		// Connect to the database by creating a new PDO object
		
		// Use a select query to get all the records from the specified table
		
		// If the results are not empty, set the given array position to the value 'tile_id'.
		// Remember that each row in the table has the 'position_row' and 'position_col' 
		// stored telling you what array position to fill.
		
		// Else if the results are empty, then set the $map array equal to the return
		// value of the function call makeMapArray(10,10,0)
		
		// Close the PDO link to the MySQL database
		
		// Return the $map array
		return $map;
	}
	
	
	
	// Create a function to export the given array $tileMapArray to an XML file.  
	// The root node of this document should be named with the value in $tableName.
	// It should have 10 'row' nodes, each with 10 'col' nodes in them. 
	// You can save the column and row numbers in the the nodes as attributes.
	// (Research the format of XML node attributes to save the column and row numbers)
	function exportMapArray($tableName, $tileMapArray)
	{
		//5.
		// Create a string variable formated with the header of a valid XML document.
		
		// concatinate the root node named with the $tableName value
		
		// Loop through the $tileMapArray, each row of the array being a set of 10 tiles,
		// and and each value of a given row being a specific tile.
		
			// Loop through each record to concatinate each value inside the <col> node
			
		// Close the root node to end the XML structure
		
		// Use the string variable to generate a SimpleXMLElement
		
		// Save the SimpleXMLElement to a file with the value of $tableName as the name
	}
	
	
?>

