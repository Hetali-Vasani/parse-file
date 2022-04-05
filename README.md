# parse-file

1. You have to put all files in the Project folder path
2. use --inputFile for adding the files which you want to parse
3. use --outputFile for adding extracted data in to output file
4. use --properties : which defines also match with all files header
5. Right now i have parse below files
   csv, xml, tsv, json (files which i have added in Example folder)
6. If you want to parse another file then need to code also for that in swictch case , since parsing of different format file have its own synatx for parsing and all. So i have  just parsing above described files.
7. Header name also dynamic, it matches with --properties in cmd. make sure that --properties and file header must have equal count and equal name otherwise it will give error msg.
8. I have create below cmd-line for testing:
	php parser.php --inputFile="new.csv" --outputFile="output.csv" --inputFile="test.xml" --properties={"model":"model_name","make":"brand_name","colour":"colour_name","capacity":"gb_spec_name","network":"network_name","grade":"grade_name","condition":"condition_name","count":"count"} --inputFile="test.json" --inputFile="products_tab_separated.tsv"



Test case (Please check attached pdf file)
1. Files not added ==> php parser.php
2. Only input File added ==> php parser.php --inputFile="new.csv"
3. Only input and output file added ==> php parser.php --inputFile="new.csv" --outputFile="output.csv"
4. Input & Output file added but wth wrong properties ==> php parser.php --inputFile="new.csv" --outputFile="output.csv" --properties={"model":"model_name","make":"brand_name","colour":"colour_name","capacity":"gb_spec_name","network":"network_name","grade":"grade_name"}
5. File not exist ==> php parser.php --inputFile="new1.csv"
6. All files and Properties added ==> php parser.php --inputFile="new.csv" --outputFile="output.csv" --properties={"model":"model_name","make":"brand_name","colour":"colour_name","capacity":"gb_spec_name","network":"network_name","grade":"grade_name","condition":"condition_name","count":"count"}
7. brand_name and model_name empty in any file 
