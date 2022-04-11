<?php
var_dump(memory_get_usage(true));
	$getOptions = (getopt(null, ['outputFile:', 'inputFile:']));
	$fileArray = (getopt(null, ['outputFile:', 'inputFile:']));
	$fileArray=getArrayValuesRecursively($fileArray);
	$headerName = '{"model":"model_name","make":"brand_name","colour":"colour_name","capacity":"gb_spec_name","network":"network_name","grade":"grade_name","condition":"condition_name","count":"count"}';
	$realOpt=json_decode($headerName,true);

	if(empty($error = checkAllFilesAndProperties($getOptions))){
		if(empty($error = checkFileExistOrNot($fileArray))){
			$csv=array();
			$getInputFile = (is_string($getOptions['inputFile']))?(array) $getOptions['inputFile']:$getOptions['inputFile'];	
			foreach ($getInputFile as $key => $file) {
				$csv[] = getFileData($file);
			}
			$getData = array_values(getUniqueValWithCount(array_merge(...$csv)));
			$getKeyExistOrNot = checkKeyExistOrNot($realOpt, $getData);
			if($getKeyExistOrNot){
				if (array_search(NULL, array_column($getData, 'model_name')) !== FALSE || array_search(NULL, array_column($getData, 'brand_name')) !== FALSE) {
					$error['error']['validation_error']='brand_name and model_name is required!';
					print_r($error);
				 	exit();
				} else {
					$fp = fopen($getOptions['outputFile'], 'w');
					$i = 0;
					$getData = str_replace(array_values($realOpt),array_keys($realOpt),json_encode($getData));
					$getData = json_decode($getData,true);
					foreach ($getData as $fields) {
					    if($i == 0){
					        fputcsv($fp, array_keys($fields));
					    }
					    fputcsv($fp, array_values($fields));
					    $i++;
					}
					fclose($fp);
					print_r($getData);
					echo "Data imported successfully!";
				}
			}else{
				$error['error']['properties_error']='Properties not match with input file';
				print_r($error);
				exit();
			}

		}else{
			print_r($error);
			exit();
		}
	}else{
		print_r($error);
		exit();
	}

	function checkKeyExistOrNot($required, $array){
		$return = true;
		foreach ($array as $key => $arr) {
			if(!empty(array_diff(array_keys($arr),$required))){
				return $return = false;
			}
		}
		return $return;
	}

	function getArrayValuesRecursively(array $array){
	    $values = [];
	    foreach ($array as $value) {
	        if (is_array($value)) {
	            $values = array_merge($values,
	                getArrayValuesRecursively($value));
	        } else {
	            $values[] = $value;
	        }
	    }
	    return $values;
	}

	function checkAllFilesAndProperties($getOptions){
		$error=[];
		if(!isset($getOptions['inputFile'])){
			$error['error']['input_files_error'] ='There is no input file exist for prasing!';
		}if(!isset($getOptions['outputFile'])){
			$error['error']['output_files_error'] ='There is no output file for store data!';
		}
		return $error;
	}

	function checkFileExistOrNot($fileArray){
		$error = [];
		foreach ($fileArray as $key => $file) {
			if(!file_exists($file)){
				$error['error']['file'][$file]['file_not_exist'] = "File : ".$file." Not exist!";
			}
		}
		return $error;
	}
	
	function getFileFormat($file){
		$getFileExt = pathinfo($file);
		return $getFileExt['extension'];
	}

	function getFileData($file){
		$getFileExt=getFileFormat($file);
		switch ($getFileExt) {
			case 'csv':
				$csv = csvToArray($file);
				return $csv;
				break;

			case 'tsv':
				$tsv = tsvToArray($file);
				return $tsv;
				break;
			
			case 'json':
				$string = file_get_contents($file);
				$json = json_decode($string, true);
				return $json;
				break;

			case 'xml':
				$buffer = file_get_contents($file);
				$xml   = simplexml_load_string($buffer);
				$array = json_decode(json_encode((array) $xml), true);
				$strReplce = str_replace(':[]',':null',json_encode($array));
				$xmlData = json_decode($strReplce,true);
				return array_shift($xmlData);			  
				break;

			default:
				break;
		}
	}

	function tsvToArray($tsvFile){
		$tsvData = array();
		if(($handle = fopen($tsvFile, 'r')) !== false){
		    $rows = fgetcsv($handle, 0, "\t");
		    while(($csv = fgetcsv($handle, 0, "\t")) !== false){
		    	$tsvData[] = array_combine($rows, $csv);
		    }
		    fclose($handle);
		}
	    return $tsvData;
	}
	function csvToArray($csvFile){
		$csvData = array();
		if(($handle = fopen($csvFile, 'r')) !== false){
		    $rows = fgetcsv($handle);
		    while(($csv = fgetcsv($handle)) !== false){
		    	$csvData[] = array_combine($rows, $csv);
		    }
		    fclose($handle);
		}
	    return $csvData;
	}

	function getUniqueValWithCount($data){
	    $serialize = array_map("serialize", $data);
		$count     = array_count_values ($serialize);
		$data    = array_unique($serialize);
		foreach($data as &$u)
		{
		    $u_count = $count[$u];
		    $u = unserialize($u);
		    $u['count'] = $u_count;
		}
	    return $data;
	}
var_dump(memory_get_usage(true));
?>
