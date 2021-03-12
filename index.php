<?php
// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Additional headers which may be sent along with the CORS request
// The X-Requested-With header allows jQuery requests to go through
header('Access-Control-Allow-Headers: *');

use Slim\Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

require 'vendor/autoload.php';
require("db.php");

$configuration = [
  'settings' => [
    'displayErrorDetails' => true,
  ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '\view\image\uploads';

function moveFile($directory, UploadedFile $uploadedFile)
{	
  $array = explode('.', $uploadedFile->getClientFilename());
  $extension = end($array);
  $basename = bin2hex(random_bytes(8)).date("_dmy_His"); // see http://php.net/manual/en/function.random-bytes.php
  $filename = sprintf('%s.%0.8s', $basename, $extension);
  $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
  return $filename;
}

function utf8_encode_deep(&$input)
{
  if (is_string($input)) {
    $input = utf8_encode($input);
  } else if (is_array($input)) {
    foreach ($input as &$value) {
      utf8_encode_deep($value);
    }

    unset($value);
  } else if (is_object($input)) {
    $vars = array_keys(get_object_vars($input));

    foreach ($vars as $var) {
      utf8_encode_deep($input->$var);
    }
  }
}

function sendEmail($row)
{
  // Create the Transport
  $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername('shayanshaikh.uae@gmail.com')
    ->setPassword('fouhhnvwvfulziuj');

  // Create the Mailer using your created Transport
  $mailer = new Swift_Mailer($transport);

  // // Create a message
  $message = (new Swift_Message('Payslip From Fitness Gym'))
    ->setFrom(['shayanshaikh.uae@gmail.com' => 'Shayan Shaikh'])
    ->setTo($row->email)
    ->setBody('<!DOCTYPE html>
      <html>
        <head>
          <title>Fitness Gym</title>
          <meta charset="utf-8">
        </head>
        <body>
          <table width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#f2f4f8">
            <tbody>
              <tr><td height="50"></td></tr>
              <tr>
                <td align="center" valign="top">
                  <table width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border:1px solid #f1f2f5">
                    <tbody>
                      <tr>
                        <td colspan="3" height="60" bgcolor="#ffffff" style="border-bottom:1px solid #eeeeee;padding-left:16px"
                          align="center">
                          Fitness Gym
                        </td>
                      </tr>
                      <tr><td colspan="3" height="20"></td></tr>
                      <tr>
                        <td width="20"></td>
                        <td align="left">
                          <table cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td colspan="3" style="text-align:center">
                                  <span style="font-family:Helvetica,Arial,sans-serif;font-weight:bold;font-size:28px;line-height:28px;color:#333333">Payslip</span></td>
                              </tr>
                              <tr><td colspan="3" height="20"></td></tr>
                              <tr><td colspan="3" height="1" bgcolor="#eeeeee" style="font-size:1px;line-height:1px">&nbsp;</td></tr>
                              <tr><td colspan="3" height="20"></td></tr>
                              <tr>
                                <td colspan="3">
                                <table class="table table-striped table-bordered exportToCSV" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
                                <thead>
                                  <tr>
                                    <td width="30%" style="text-align:center;">Earning</td>
                                    <td width="20%" style="text-align:right;">Amount</td>
                                    <td width="30%" style="text-align:center;">Deduction</td>
                                    <td width="20%" style="text-align:right;">Amount</td>
                                  </tr>
                                </thead>
                                <tbody class="border">
                                  <!-- ITEMS HERE -->
                                  <tr>
                                    <td>Monthly Fee</td>
                                    <td class="text-right monthly_fee">'.$row->monthly_fee.'</td>
                                    <td>Late Charges</td>
                                    <td class="text-right"> - 0.00 </td>
                                  </tr>
                                  <tr>
                                    <td>Trainer Fee</td>
                                    <td class="text-right trainer_fee">'.$row->trainer_fee.'</td>
                                    <td> </td>
                                    <td> </td>
                                  </tr>
                                  <tr>
                                    <td>Other Fee</td>
                                    <td class="text-right other_fee">'.$row->other_fee.'</td>
                                    <td> </td>
                                    <td> </td>
                                  </tr>
                                  <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                  </tr>
                                </tbody>
                                <tbody class="border">
                                  <tr>
                                    <td><b>Total Fee</b></td>
                                    <td class="text-right total_fee"><b>'.$row->total_fee.' </b></td>
                                    <td><b>Total Deduction</b></td>
                                    <td class="text-right"> - 0.0</td>
                                  </tr>
                                  <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                  </tr>
                                </tbody>
                              </table>
                                </td>
                              </tr>
                              <tr><td colspan="3" height="20"></td></tr>
                              <tr>
                                <td colspan="3" style="text-align:center">
                                  <span style="font-family:Helvetica,Arial,sans-serif;font-size:12px;color:#cccccc">This message
                                    was sent from Fitness Gym System</span>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                        <td width="20"></td>
                      </tr>
                      <tr><td colspan="3" height="20"></td></tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr><td height="50"></td></tr>
            </tbody>
          </table>
        </body>
      </html>', 'text/html');

  // Send the message
  $result = $mailer->send($message);
  return $result;
}


// #################################################################################################################
// POST  DATA                #######################################################################################
// #################################################################################################################

$app->post('/login', function (Request $request, Response $response, array $args) {
  
  require_once('api/database.php');
  $post = (array)$request->getParsedBody();
  $login_id = $post['login_id'];
  $password = md5($post['password']);
  $status = false;

  $rows = $db->fetch("SELECT
                        u.id, u.name, u.email, u.verified, u.token
                        , (SELECT r.role_name FROM role r WHERE r.id = ur.role_id) AS role
                      FROM `user` u
                      LEFT JOIN `user_role` ur ON u.id = ur.user_id
                      WHERE u.email = '$login_id' AND u.password = '$password'");

  if (sizeof($rows) < 1) {
    $message = "Incorrect Email Or Password";
    return $response->withJson(array('status' => $status, 'row' => [], 'message' => $message));
  } else {
    $status = true;
    $message = "success";
    $result = ['token' => $rows[0]->verified ? $rows[0]->token : false,  'expires' => date("Y-m-d\T23:59:59.000\Z", strtotime("+3 day")), 'user' => $rows[0]];
    return $response->withJson(array('status' => $status, 'row' => $result, 'message' => $message));
  }
});

$app->post('/add-gymboy', function (Request $req, Response $res, array $args) {
	require_once('api/database.php');
  $directory = $this->get('upload_directory');
  $post = $req->getParsedBody();
  $files = $req->getUploadedFiles();
  $file = $files['file'];
  // $image = file_get_contents($files['image']->file);
  $image = '';
	if ($file->getError() === UPLOAD_ERR_OK) {
    $image = moveFile($directory, $file);
  }

  $name = $post['name'];
  $father_name = $post['father_name'];
  $mobile_number = $post['mobile_number'];
  $joining_date = date('Y-m-d', strtotime($post['joining_date']));
  $fee = $post['fee'];
  $weight = $post['weight'];
  $email = $post['email'];
  $address = $post['address'];
  $gender = $post['gender'];
  $other_details = $post['other_details'];
  $role = 3;
  
  
  $sql = "INSERT INTO `user` (`name`,`father_name`, `mobile_number`, `joining_date`, `fee`, `weight`, `email`, `image`, `address`, `gender`, `other_details`, `created_at`) 
			    VALUES ('$name', '$father_name', '$mobile_number', '$joining_date', '$fee', '$weight', '$email', '$image', '$address', '$gender', '$other_details', NOW())";
  $result = $conn->query($sql);
  $last_id = $conn->insert_id;
  
  if ($result) {
    $sql = "INSERT INTO `user_role` (`user_id`,`role_id`,`created_at`) 
    VALUES ('$last_id', '$role', NOW() )";
    $result = $conn->query($sql);
  } else {
    $result = false;
  }

  $message = $result ? 'User added successfully' : "<br>" . $conn->error;
  $status = $result ? true : false;

  return $res->withJson(array('status' => $status, 'row' => $result, 'message' => $message));

});

$app->post('/add-trainer', function (Request $req, Response $res, array $args) {
	require_once('api/database.php');
  $directory = $this->get('upload_directory');
  $post = $req->getParsedBody();
  $files = $req->getUploadedFiles();
  $file = $files['file'];
  // $image = file_get_contents($files['image']->file);
  $image = '';
	if ($file->getError() === UPLOAD_ERR_OK) {
    $image = moveFile($directory, $file);
  }

  $name = $post['name'];
  $father_name = $post['father_name'];
  $mobile_number = $post['mobile_number'];
  $joining_date = date('Y-m-d', strtotime($post['joining_date']));
  $fee = $post['fee'];
  $weight = $post['weight'];
  $email = $post['email'];
  $address = $post['address'];
  $gender = $post['gender'];
  $other_details = $post['other_details'];
  $role = 2;

  $sql = "INSERT INTO `user` (`name`,`father_name`, `mobile_number`, `joining_date`, `fee`, `weight`, `email`, `image`, `address`, `gender`, `other_details`, `created_at`) 
			    VALUES ('$name', '$father_name', '$mobile_number', '$joining_date', '$fee', '$weight', '$email', '$image', '$address', '$gender', '$other_details', NOW())";
  $result = $conn->query($sql);
  $last_id = $conn->insert_id;
  
  if ($result) {
    $sql = "INSERT INTO `user_role` (`user_id`,`role_id`,`created_at`) 
    VALUES ('$last_id', '$role', NOW() )";
    $result = $conn->query($sql);

    // inser shifts ####################################################
    $a1 = $post['monday_available'] ? 1 : 0; $a2 = $post['tuesday_available'] ? 1 : 0;
    $startTime1 = explode(";", $post['monday_interval'])[0]; $endTime1 = explode(";", $post['monday_interval'])[1];
    $startTime2 = explode(";", $post['tuesday_interval'])[0]; $endTime2 = explode(";", $post['tuesday_interval'])[1];
    $sql = "INSERT INTO `trainer_shift` (`trainer_id`,`title`, `is_available`, `startTime`, `endTime`) 
    VALUES ('$last_id', 'Shift1', '$a1', '$startTime1', '$endTime1'), ('$last_id', 'Shift2', '$a2', '$startTime2', '$endTime2')";
    $result = $conn->query($sql);

  } else {
    $result = false;
  }
  
  $message = $result ? 'User added successfully' : "<br>" . $conn->error;
  $status = $result ? true : false;

  return $res->withJson(array('status' => $status, 'row' => $result, 'message' => $message));

});


$app->post('/assign-trainer', function (Request $req, Response $res, array $args) {
  require_once('api/database.php');
  $directory = $this->get('upload_directory');
  $post = $req->getParsedBody();

  $gymboy_id = $post['gymboy_id'];
  $trainer_id = $post['trainer_id'];
  $trainer_fee = $post['trainer_fee'];


  $result = $conn->query("SELECT id FROM trainer_gymboy WHERE gymboy_id ='$gymboy_id'");

  if ($result && $result->num_rows > 0) {
    $sql = "UPDATE `trainer_gymboy` 
            SET `gymboy_id` = '$gymboy_id'
              , `trainer_id` = '$trainer_id'
              , `trainer_fee` = '$trainer_fee'
              , `modified_at` = NOW()
            WHERE `gymboy_id` = '$gymboy_id'";
  } else {
    $sql = "INSERT INTO `trainer_gymboy` (`trainer_id`, `gymboy_id`, `trainer_fee`, `created_at`) 
            VALUES ('$trainer_id', '$gymboy_id', '$trainer_fee', NOW())";
  }

  $result = $conn->query($sql) ? array('Last Id' => $conn->insert_id) : false;
  $status = $result ? true : false; 
  $message = $result ? "Trainer successfully assigned to gymboy" : "<br>" . $conn->error;

  return $res->withJson(array('status' => $status, 'row' => $result, 'message' => $message));

});

$app->post('/toggle-activation-gymboy', function (Request $req, Response $res, array $args) {
  require_once('api/database.php');
  $post = $req->getParsedBody();
  $userId = $post['userId'];

  $sql = "UPDATE `user` u
          SET u.`is_active` = IF(u.`is_active` = 1, 0, 1)
          WHERE u.`id` = '$userId'";

  $result = $conn->query($sql) ? array('Last Id' => $conn->insert_id) : false;
  $status = $result ? true : false; 
  $message = $result ? "Gymboy Status Toggled" : "<br>" . $conn->error;

  return $res->withJson(array('status' => $status, 'row' => $result, 'message' => $message));

});


$app->post('/add-fee', function (Request $req, Response $res, array $args) {
	require_once('api/database.php');
  $post = $req->getParsedBody();
  $gymboy_id = $post['gymboy_id'];
  $payment_date = date('Y-m-d', strtotime($post['payment_date']));
  $monthly_fee = $post['monthly_fee'];
  $trainer_fee = $post['trainer_fee'];
  $other_fee = $post['other_fee'];
  $description = $post['description'];
  
  $sql = "INSERT INTO `gymboy_fee` (`gymboy_id`,`payment_date`, `monthly_fee`, `trainer_fee`, `other_fee`, `description`, `created_at`) 
			    VALUES ('$gymboy_id', '$payment_date', '$monthly_fee', '$trainer_fee', '$other_fee', '$description', NOW())";
  $result = $conn->query($sql);
  $last_id = $conn->insert_id;

  $message = $result ? 'Fees added successfully: '.$last_id : "<br>" . $conn->error;
  $status = $result ? true : false;

  return $res->withJson(array('status' => $status, 'row' => $result, 'message' => $message));

});

// #################################################################################################################
// GET  DATA                #######################################################################################
// #################################################################################################################

$app->get('/user-access/{role}/{token}', function (Request $request, Response $response, array $args) {
	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));
      
	$role = $args['role'];
	$rows = $db->fetch("SELECT 
                           a.`name`
                          ,a.`page`
                          ,a.`icon`
                      FROM `role_access` ra 
                      LEFT JOIN role r ON r.id = ra.`role_id` 
                      LEFT JOIN `access` a ON a.`id` = ra.`access_id`
                      WHERE r.role_name = '$role'");
	return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});


$app->get('/gymboy-list/{startDate}/{endDate}/{search}/{sort}/{skip}/{take}/{token}', function (Request $request, Response $response, array $args) {
	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $startDate = $args['startDate'];
  $endDate = $args['endDate'];
  $search = $args['search'] != 'All' ? $args['search'] : '';
	$sort = $args['sort'] == 1 ? 'u.`joining_date` DESC' : 'u.`joining_date` ASC' ;
	$skip = $args['skip'];
	$take = $args['take'];
	$rows = $db->fetch("SELECT 
                             *
                            , u.id
                            , u.created_at
                            ,(SELECT u2.name FROM user u2 WHERE u2.id = tg.trainer_id) as trainer_name 
                      FROM `user` u
                      LEFT JOIN user_role r ON r.user_id = u.`id`
                      LEFT JOIN trainer_gymboy tg ON tg.gymboy_id = u.id 
                      WHERE (u.name LIKE '$search%' OR u.father_name LIKE '$search%' OR u.weight LIKE '$search%' OR u.email LIKE '$search%' OR u.address LIKE '$search%')
                      AND (u.joining_date BETWEEN '$startDate' AND '$endDate')
                      AND r.role_id = 3
                      ORDER BY $sort LIMIT $skip, $take");
	return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

$app->get('/trainer-list/{startDate}/{endDate}/{search}/{sort}/{skip}/{take}/{token}', function (Request $request, Response $response, array $args) {
	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $startDate = $args['startDate'];
  $endDate = $args['endDate'];
  $search = $args['search'] != 'All' ? $args['search'] : '';
	$sort = $args['sort'] == 1 ? 'u.`joining_date` DESC' : 'u.`joining_date` ASC' ;
	$skip = $args['skip'];
	$take = $args['take'];
	$rows = $db->fetch("SELECT *, u.id 
                              , (SELECT CONCAT(st1.`startTime`, ' - ', st1.`endTime`) FROM `trainer_shift` st1 WHERE st1.`trainer_id` = u.`id` AND st1.title = 'Shift1' LIMIT 1) AS shift_1
                              , (SELECT CONCAT(st2.`startTime`, ' - ', st2.`endTime`) FROM `trainer_shift` st2 WHERE st2.`trainer_id` = u.`id` AND st2.title = 'Shift2' LIMIT 1) AS shift_2
                      FROM `user` u
                      LEFT JOIN user_role r ON r.user_id = u.`id` 
                      WHERE (u.name LIKE '$search%' OR u.father_name LIKE '$search%' OR u.weight LIKE '$search%' OR u.email LIKE '$search%' OR u.address LIKE '$search%')
                      AND (u.joining_date BETWEEN '$startDate' AND '$endDate')
                      AND r.role_id = 2
                      ORDER BY $sort LIMIT $skip, $take");
	return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});


$app->get('/fee-list/{startDate}/{endDate}/{search}/{sort}/{skip}/{take}/{token}', function (Request $request, Response $response, array $args) {
	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $startDate = $args['startDate'];
  $endDate = $args['endDate'];
  $search = $args['search'] != 'All' ? $args['search'] : '';
	$sort = $args['sort'] == 1 ? 'gf.`payment_date` DESC' : 'gf.`payment_date` ASC' ;
	$skip = $args['skip'];
	$take = $args['take'];
	$rows = $db->fetch("SELECT *
                            ,ROUND(monthly_fee + trainer_fee + other_fee, 2) as total_fee
                            ,gf.id as payslipId
                      FROM `gymboy_fee` gf
                      LEFT JOIN user u ON u.`id` = gf.`gymboy_id` 
                      WHERE (gf.description LIKE '$search%')
                      AND (gf.payment_date BETWEEN '$startDate' AND '$endDate')
                      ORDER BY $sort LIMIT $skip, $take");
	return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

$app->get('/trainer-by-name/{search}/{skip}/{take}/{token}', function (Request $request, Response $response, array $args) {
  require_once('api/database.php');
  require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $search = $args['search'] != 'All' ? $args['search'] : '';
  $skip = $args['skip'];
  $take = $args['take'];
  $rows = $db->fetch("SELECT u.* FROM `user` u
                      LEFT JOIN user_role r ON r.user_id = u.`id` 
                      WHERE (u.name LIKE '$search%' OR u.father_name LIKE '$search%' OR u.weight LIKE '$search%' OR u.email LIKE '$search%' OR u.address LIKE '$search%')
                      AND r.role_id = 2
                      LIMIT $skip, $take");
  return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

$app->get('/gymboy-by-name/{search}/{skip}/{take}/{token}', function (Request $request, Response $response, array $args) {
  require_once('api/database.php');
  require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $search = $args['search'] != 'All' ? $args['search'] : '';
  $skip = $args['skip'];
  $take = $args['take'];
  $rows = $db->fetch("SELECT u.* FROM `user` u
                      LEFT JOIN user_role r ON r.user_id = u.`id` 
                      WHERE (u.name LIKE '$search%' OR u.father_name LIKE '$search%' OR u.weight LIKE '$search%' OR u.email LIKE '$search%' OR u.address LIKE '$search%')
                      AND r.role_id = 3
                      LIMIT $skip, $take");
  return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

$app->get('/payslip/{id}/{token}', function (Request $request, Response $response, array $args) {
	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $id = $args['id'];
	$rows = $db->fetchOneObj("SELECT u.*
                      ,gf.monthly_fee
                      ,gf.trainer_fee
                      ,gf.other_fee
                      ,ROUND(monthly_fee + trainer_fee + other_fee, 2) AS total_fee
                      ,gf.payment_date
                      ,gf.id as payslipId
                    FROM `gymboy_fee` gf
                    LEFT JOIN USER u ON u.`id` = gf.`gymboy_id` 
                    WHERE gf.id = $id");
	return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

$app->get('/dashboard-chart/{startDate}/{endDate}/{token}', function (Request $request, Response $response, array $args) {
  require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $startDate = $args['startDate'];
  $endDate = $args['endDate'];

  $rows = $db->fetch("SELECT 
                        DATE_FORMAT(gf.`payment_date`, '%Y-%m-%d') AS 'day'
                        , COUNT(gf.`id`) AS count_payments
                        , SUM(gf.`monthly_fee` + gf.`other_fee` + gf.`trainer_fee`) AS amount_payments
                      FROM `gymboy_fee` gf
                      WHERE (gf.payment_date BETWEEN '$startDate' AND '$endDate')
                      GROUP BY DAY");
  return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

$app->get('/dashboard-report/{startDate}/{endDate}/{token}', function (Request $request, Response $response, array $args) {
  require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $startDate = $args['startDate'];
  $endDate = $args['endDate'];

  $rows = $db->fetchOneObj("SELECT 
                          COUNT(gf.`id`) AS count_payments
                        , SUM(gf.`monthly_fee` + gf.`other_fee` + gf.`trainer_fee`) AS amount_payments
                        , (SELECT COUNT(u.id) FROM user u WHERE u.created_at BETWEEN '$startDate' AND '$endDate') as admissions
                      FROM `gymboy_fee` gf
                      WHERE (gf.payment_date BETWEEN '$startDate' AND '$endDate')");
  return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});

// email section ##############################################################################################################
$app->get('/emailPayslip/{id}/{token}', function (Request $request, Response $response, array $args) {

	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

  $id = $args['id'];
	$rows = $db->fetchOneObj("SELECT u.*
                      ,gf.monthly_fee
                      ,gf.trainer_fee
                      ,gf.other_fee
                      ,ROUND(monthly_fee + trainer_fee + other_fee, 2) AS total_fee
                      ,gf.payment_date
                      ,gf.id as payslipId
                    FROM `gymboy_fee` gf
                    LEFT JOIN USER u ON u.`id` = gf.`gymboy_id` 
                    WHERE gf.id = $id");

  // send email
  if ($rows->email) {
    $status = sendEmail($rows);
    $message = $status ? 'Success' : 'Error In Sending';
  } else {
    $status = false;
    $message = 'User Dont have any Email';
  }

  return $response->withJson(array('status' => $status, 'message' => $message));
});

$app->run();
