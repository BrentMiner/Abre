<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

	//Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

  $headlineInformationJSON = $_POST['json'];

  $headlineInformationArray = json_decode($headlineInformationJSON, true);
  $headlinePurpose = $headlineInformationArray['headline_purpose'];
  unset($headlineInformationArray['headline_purpose']);
  $headlineID = $headlineInformationArray['headline_id'];
  unset($headlineInformationArray['headline_id']);
  $headlineFormID = $headlineInformationArray['headline_formID'];
  unset($headlineInformationArray['headline_formID']);
  $headlineInformationJSON = json_encode($headlineInformationArray);

  $submitter = $_SESSION['useremail'];

  if($headlinePurpose == "form"){
    //User Information
		$usertype = $_SESSION['usertype'];
		$firstname = "";
		$lastname = "";
		$uniqueid = "";

		//Get Staff Information
		if($usertype == 'staff'){
			$firstname = GetStaffFirstName($submitter);
			$lastname = GetStaffLastName($submitter);
			$uniqueid = GetStaffUniqueID($submitter);
		}

		//Get Student Information
		if($usertype == 'student'){
			$firstname = GetStudentFirstName($submitter);
			$lastname = GetStudentLastName($submitter);
			$uniqueid = GetStudentUniqueID($submitter);
		}

		//Get Staff Information
		if($usertype == 'parent' or ($firstname == "" && $lastname == "")){
			$name = $_SESSION['displayName'];
			$name = explode(" ", $name);
			$firstname = $name[0];
			$lastname = $name[1];
		}

		//Add entry to responses
		$stmt = $db->stmt_init();
		$sql = "INSERT INTO forms_responses (FormID, Submitter, FirstName, LastName, UniqueID, UserType, Response) VALUES (?, ?, ?, ?, ?, ?, ?);";
		$stmt->prepare($sql);
		$stmt->bind_param("issssss", $headlineFormID, $submitter, $firstname, $lastname, $uniqueid, $usertype, $headlineInformationJSON);
		$stmt->execute();
    if($stmt->error != ""){
      $response = array("status" => "Error", "message" => "There was a problem saving your form response!");
      header("Content-Type: application/json");
      echo json_encode($response);
      $stmt->close();
      $db->close();
      exit;
    }
		$stmt->close();
  }

	//Insert the headline_response
	$stmt = $db->stmt_init();
	$sql = "INSERT INTO headline_responses (headline_id, email) VALUES (?, ?)";
	$stmt->prepare($sql);
	$stmt->bind_param("is", $headlineID, $submitter);
	$stmt->execute();
  if($stmt->error != ""){
    $response = array("status" => "Error", "message" => "There was a problem saving your headline response!");
    header("Content-Type: application/json");
    echo json_encode($response);
    $stmt->close();
    $db->close();
    exit;
  }
	$stmt->close();
	$db->close();

  $response = array("status" => "Success", "message" => "Your response was saved successfully!");
  header("Content-Type: application/json");
  echo json_encode($response);

?>