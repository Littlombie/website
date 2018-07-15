
<?php
header("Content-type: text/html; charset=utf-8");

if( empty($_POST['name']) )
{
    die();
}
$bfconfig = Array (
    'sitename' => $_POST['name'],//发送人姓名
);

$mail = Array (
    'state' => 1,
    'server' => 'smtp.163.com',//smtp服务器
    'port' => 25,
    'auth' => 1,
    'username' => 'deanwen258',//邮件发送用户名
    'password' => 'wenyad7ijiko9ki8',//邮件发送密码
    'charset' => 'utf-8',
    'mailfrom' => 'deanwen258@163.com'//发送人邮箱
);

$mail_to = '417499996@qq.com';//接收人邮箱 78648038@qq.com/2281206582@qq.com //
$mail_subject = '网站来访';//邮件标题
$mail_message = '<table border="1" cellspacing="10" style="width: 600px;border-collapse: collapse;margin: 0 auto;font-size: 14px;font-family: Microsoft YaHei,Hiragino Sans GB;color: #000;" >
                <tr style="text-align: center;"> 
                    <td colspan="2" style="font-size: 24px;line-height: 2; color: #ee469a; text-align: center;font-weight: 600;font-family: \'MicroSoft YaHei\';">网站来访</td>
                </tr>
                 <tr>
                   <td style="width:120px;text-align: center;line-height: 2;">姓名</td>
                   <td style="padding: 5px;">' . $_POST['name'] . '</td>
                 </tr>
                 <tr>
                   <td style="width:120px;text-align: center;line-height: 2;">邮箱</td>
                   <td style="padding: 5px;">' . $_POST['mail'] . '</td>
                 </tr>
                 <tr>
                   <td style="width:120px;text-align: center;line-height: 2;">主题</td>
                   <td style="padding: 5px;">' . $_POST['subject'] . '</td>
                 </tr>
                 <tr height="100">
                   <td style="width:120px;text-align: center;line-height: 2;">留言</td>
                   <td style="padding: 5px;">' . $_POST['message'] . '</td>
                 </tr>
                 </table>';//邮件内容


sendmail($mail_to, $mail_subject, $mail_message);


function sendmail($mail_to, $mail_subject, $mail_message) {

    global $mail, $bfconfig;

    date_default_timezone_set('PRC');

    $mail_subject = '=?'.$mail['charset'].'?B?'.base64_encode($mail_subject).'?=';
    $mail_message = chunk_split(base64_encode(preg_replace("/(^|(\r\n))(\.)/", "\1.\3", $mail_message)));

    $headers = "";
    $headers .= "MIME-Version:1.0\r\n";
    $headers .= "Content-type:text/html\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";
    $headers .= "From: ".$bfconfig['sitename']."<".$mail['mailfrom'].">\r\n";
    $headers .= "Date: ".date("r")."\r\n";
    list($msec, $sec) = explode(" ", microtime());
    $headers .= "Message-ID: <".date("YmdHis", $sec).".".($msec * 1000000).".".$mail['mailfrom'].">\r\n";

    if(!$fp = fsockopen($mail['server'], $mail['port'], $errno, $errstr, 30)) {
        exit("CONNECT - Unable to connect to the SMTP server");
    }

    stream_set_blocking($fp, true);

    $lastmessage = fgets($fp, 512);
    if(substr($lastmessage, 0, 3) != '220') {
        exit("CONNECT - ".$lastmessage);
    }

    fputs($fp, ($mail['auth'] ? 'EHLO' : 'HELO')." befen\r\n");
    $lastmessage = fgets($fp, 512);
    if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
        exit("HELO/EHLO - ".$lastmessage);
    }

    while(1) {
        if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
            break;
        }
        $lastmessage = fgets($fp, 512);
    }

    if($mail['auth']) {
        fputs($fp, "AUTH LOGIN\r\n");
        $lastmessage = fgets($fp, 512);
        if(substr($lastmessage, 0, 3) != 334) {
            exit($lastmessage);
        }

        fputs($fp, base64_encode($mail['username'])."\r\n");
        $lastmessage = fgets($fp, 512);
        if(substr($lastmessage, 0, 3) != 334) {
            exit("AUTH LOGIN - ".$lastmessage);
        }

        fputs($fp, base64_encode($mail['password'])."\r\n");
        $lastmessage = fgets($fp, 512);
        if(substr($lastmessage, 0, 3) != 235) {
            exit("AUTH LOGIN - ".$lastmessage);
        }

        $email_from = $mail['mailfrom'];
    }

    fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
    $lastmessage = fgets($fp, 512);
    if(substr($lastmessage, 0, 3) != 250) {
        fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
        $lastmessage = fgets($fp, 512);
        if(substr($lastmessage, 0, 3) != 250) {
            exit("MAIL FROM - ".$lastmessage);
        }
    }

    foreach(explode(',', $mail_to) as $touser) {
        $touser = trim($touser);
        if($touser) {
            fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
            $lastmessage = fgets($fp, 512);
            if(substr($lastmessage, 0, 3) != 250) {
                fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
                $lastmessage = fgets($fp, 512);
                exit("RCPT TO - ".$lastmessage);
            }
        }
    }

    fputs($fp, "DATA\r\n");
    $lastmessage = fgets($fp, 512);
    if(substr($lastmessage, 0, 3) != 354) {
        exit("DATA - ".$lastmessage);
    }

    fputs($fp, $headers);
    fputs($fp, "To: ".$mail_to."\r\n");
    fputs($fp, "Subject: $mail_subject\r\n");
    fputs($fp, "\r\n\r\n");
    fputs($fp, "$mail_message\r\n.\r\n");
    $lastmessage = fgets($fp, 512);
    if(substr($lastmessage, 0, 3) != 250) {
        exit("END - ".$lastmessage);
    }

    fputs($fp, "QUIT\r\n");

}

echo "<script>alert('提交成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
?>