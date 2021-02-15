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

function sendVerificationEmail($userEmail, $pin_code)
{
  // Create the Transport
  $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername('shayanshaikh.uae@gmail.com')
    ->setPassword('fouhhnvwvfulziuj');

  // Create the Mailer using your created Transport
  $mailer = new Swift_Mailer($transport);

  // // Create a message
  $message = (new Swift_Message('Verify Your Email Address'))
    ->setFrom(['shayanshaikh.uae@gmail.com' => 'Shayan Shaikh'])
    ->setTo($userEmail)
    ->setBody('<!DOCTYPE html>
      <html>
        <head>
          <title>My Medical Data</title>
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
                          <img
                            src="http://apps.techifycloud.com:29000/img/myMedical.jpg"
                            width="140" height="41" style="display:block;width:250px;height:auto" class="CToWUd">
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
                                  <span style="font-family:Helvetica,Arial,sans-serif;font-weight:bold;font-size:28px;line-height:28px;color:#333333">Welcome
                                    to My Medical Data</span></td>
                              </tr>
                              <tr><td colspan="3" height="20"></td></tr>
                              <tr><td colspan="3" height="1" bgcolor="#eeeeee" style="font-size:1px;line-height:1px">&nbsp;</td></tr>
                              <tr><td colspan="3" height="20"></td></tr>
                              <tr>
                                <td colspan="3">
                                  <p style="font-family:Helvetica,Arial,sans-serif;color:#494747;line-height:140%;text-align:center">
                                    Your Medical Data App Account Opening one-time password (OTP) is </p>
                                  <table width="100%" style="width:100%">
                                    <tbody>
                                      <tr>
                                        <td align="center">
                                          <b> '.$pin_code.' </b> Please DO NOT share your OTP with anyone.
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr><td colspan="3" height="20"></td></tr>
                              <tr>
                                <td colspan="3" style="text-align:center">
                                  <span style="font-family:Helvetica,Arial,sans-serif;font-size:12px;color:#cccccc">This message
                                    was sent from MY Medical Data App</span>
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
  
  $sql = "INSERT INTO `user` (`name`,`father_name`, `mobile_number`, `joining_date`, `fee`, `weight`, `email`, `image`, `address`, `gender`, `other_details`, `created_at`) 
			    VALUES ('$name', '$father_name', '$mobile_number', '$joining_date', '$fee', '$weight', '$email', '$image', '$address', '$gender', '$other_details', NOW())";
  $result = $conn->query($sql);
  $message = $result ? 'Medicine added successfully' : "<br>" . $conn->error;
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


$app->get('/gymboy-list/{sort}/{skip}/{take}/{token}', function (Request $request, Response $response, array $args) {
	require_once('api/database.php');
	require('api/authentication.php');
  if (!$auth->num_rows) return $response->withJson(array('status' => false, 'row' => [], 'message' => 'invalid token'));

	$sort = $args['sort'] == 1 ? 'u.`joining_date` DESC' : 'u.`joining_date` ASC' ;
	$skip = $args['skip'];
	$take = $args['take'];
	$rows = $db->fetch("SELECT * FROM `user` u
                      ORDER BY $sort LIMIT $skip, $take");
	return $response->withJson(array('status' => true, 'row' => $rows, 'message' => ''));
});


$app->run();
