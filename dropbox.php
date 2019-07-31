<?php

// Replace your token
$token = "OknH-OP5TvAAAAAAAAAAChNpI7cuE84xhy_NtlO6LiA6v_HywsDk2h3Drtd6qKau";

// Global variables
$root_directory = '/Jobs';
$handle = fopen ("php://stdin","r");

echo "Please input the defulat the root directory of the Jobs (default:'/Jobs): ";

// Root Directory
$line = input_command($handle);
if(strlen($line) > 0 ){
    $root_directory = "/".$line;    
}
display_log ("Root Directory : ".'"'.$root_directory.'"');

$option = -1;
while(true) {
    echo "Please select option ( Archive:0, UnArchive:1, Retrieve:2) : " ;
    $line = input_command($handle);
    $option = intval($line);
    echo $option;
    if($option >= 0 && $option <= 2 ){
        break;
    }
    echo "Try Again";
    echo "\n";
}

if($option == 0) archive_job();
if($option == 1) unarchive_job();
if($option == 2) retrive_job();

function input_command($handle) {
    global $handle;
    $line = fgets($handle);
    $line = substr($line, 0, -1);
    //rtrim($line, "\n");
    return $line;
}

function archive_job() {
    
    global $root_directory;

    display_log("You are in a ARCHIVE mode");

    echo "Company Name : ";      $company_name = input_command();
    echo "FOLDER ID : ";        $job_folder = input_command();
    echo "Category Name : ";    $category = input_command();
    echo "Year : ";  $year = input_command();

    $from_path = $root_directory."/Active/".$company_name."/".$job_folder;
    if(strlen($category) > 0 )
        $to_path = $root_directory."/Archives/".$company_name."/".$category."/".$year;
    else
        $to_path = $root_directory."/Archives/".$company_name."/".$year;

    display_log("Moving folder");
    display_log("From paths: ".$from_path);
    display_log("To paths: ".$to_path);

    move_dropbox_folder($from_path, $to_path);
    
}

function unarchive_job() {
    
    global $root_directory;

    display_log("You are in a UNARCHIVE mode");

    echo "Company Name : ";     $company_name = input_command();
    echo "FOLDER ID : ";        $job_folder = input_command();
    echo "Category Name : ";    $category = input_command();
    echo "Year : ";             $year = input_command();

    $to_path = $root_directory."/Active/".$company_name."/".$job_folder;
    if(strlen($category) > 0 )
        $from_path = $root_directory."/Archives/".$company_name."/".$category."/".$year;
    else
        $from_path = $root_directory."/Archives/".$company_name."/".$year;

    display_log("Moving folder");
    display_log("From paths: ".$from_path);
    display_log("To paths: ".$to_path);

    move_dropbox_folder($from_path, $to_path);

}

function retrive_job() {

    global $root_directory;

    display_log("You are in a RETRIEVE mode");

    echo "Company Name : ";      $company_name = input_command();
    echo "FOLDER ID : ";        $job_folder = input_command();
    echo "Category Name : ";    $category = input_command();
    echo "Year : ";  $year = input_command();

    $to_path = $root_directory."/Retrieved Jobs/".$company_name."/".$job_folder;
    if(strlen($category) > 0 )
        $from_path = $root_directory."/Archives/".$company_name."/".$category."/".$year;
    else
        $from_path = $root_directory."/Archives/".$company_name."/".$year;

    display_log("Copying folder");
    display_log("From paths: ".$from_path);
    display_log("To paths: ".$to_path);

    copy_dropbox_foler($from_path, $to_path);
}

function display_log($output_string) {
    echo "-----\t".$output_string."\t-----\n";
}

function move_dropbox_folder($from_path, $to_path) {

    $parameters = array("from_path"=> $from_path,
                    "to_path"=> $to_path,
                    "allow_shared_folder"=> true,
                    "autorename"=> false,
                    "allow_ownership_transfer"=> false);

    $headers = array('Authorization: Bearer OknH-OP5TvAAAAAAAAAAChNpI7cuE84xhy_NtlO6LiA6v_HywsDk2h3Drtd6qKau',
                    'Content-Type: application/json');

    $curlOptions = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($parameters),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => true
        );

    $ch = curl_init('https://api.dropboxapi.com/2/files/move_v2');
    curl_setopt_array($ch, $curlOptions);



    
    display_log("connecting dropbox ...");
    $response = curl_exec($ch);

  
    display_log("Result");
    echo $response;
    curl_close($ch);
}

function copy_dropbox_foler($from_path, $to_path) {

    $parameters = array("from_path"=> $from_path,
                    "to_path"=> $to_path,
                    "allow_shared_folder"=> true,
                    "autorename"=> false,
                    "allow_ownership_transfer"=> false);

    $headers = array('Authorization: Bearer ',
                    'Content-Type: application/json');

    $curlOptions = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($parameters),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => true
        );

    $ch = curl_init('https://api.dropboxapi.com/2/files/copy_v2');
    curl_setopt_array($ch, $curlOptions);
    
    display_log("connecting dropbox ...");
    $response = curl_exec($ch);

  
    display_log("Result");
    echo $response;
    curl_close($ch);
}
?>

<?php
    exit (0)
?>
