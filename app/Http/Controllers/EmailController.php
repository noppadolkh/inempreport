<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\ProgressReport;
use App\Models\ArriveCheck;
use App\Models\LeaveForm;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailToManager;

class EmailController extends Controller
{

    public function dailymail()
    {

        $data['empcometoday'] = ArriveCheck::leftJoin('users','arrive_report.user_id','users.id')->select('users.name','arrive_report.*')->WhereDate('arrive_report.created_at',date('Y-m-d'))->get();
        
        \Mail::to('aongthekop@gmail.com')->send(new MailToManager($data));
        
        return 'Email sent Successfully';
    }

    public function weeklymail(){
        
    }

    function week_from_monday($date) {
        // Assuming $date is in format DD-MM-YYYY
        list($day, $month, $year) = explode("-", $date);
    
        // Get the weekday of the given date
        $wkday = date('l',mktime('0','0','0', $month, $day, $year));
    
        switch($wkday) {
            case 'Monday': $numDaysToMon = 0; break;
            case 'Tuesday': $numDaysToMon = 1; break;
            case 'Wednesday': $numDaysToMon = 2; break;
            case 'Thursday': $numDaysToMon = 3; break;
            case 'Friday': $numDaysToMon = 4; break;
            case 'Saturday': $numDaysToMon = 5; break;
            case 'Sunday': $numDaysToMon = 6; break;   
        }
    
        // Timestamp of the monday for that week
        $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);
    
        $seconds_in_a_day = 86400;
    
        // Get date for 7 days from Monday (inclusive)
        for($i=0; $i<7; $i++)
        {
            $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
        }
    
        return $dates;
    }
    public function sendEmail($type){
        try {
            //Server settings
            $mail = new PHPMailer(true);
            $mail->CharSet = "utf-8";
            $mail->isSMTP();                            // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                     // Enable SMTP authentication
            $mail->Username   = 'dtsmailsender@gmail.com';                     // SMTP username
            $mail->Password   = '021507047';                               // SMTP password
            $mail->SMTPSecure = 'tls';                  
            $mail->Port = 587;     //587 is used for Outgoing Mail (SMTP) Server.
            

                     
    
            $mail->From = 'dtsmailsender@gmail.com';
            $mail->FromName = 'dtsmailsender';
            $target_email = 'aongthekop@gmail.com';
            foreach($allemail as $email_id){
                $person = AuthPerson::where('id',$email_id)->first();
                if($person != null){
                    $mail->addAddress($person->email);
                }
            }
                 // Add a recipient
            $mail->addReplyTo('dtsmailsender@gmail.com', 'dtsmailsender');
            
            
            
            
    
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Refer selective person in Mold data based';

            $bodyhtml = '<h2>To Whom it may concern, Informed Mold PM as detail below </h2><br><table style="border:1px solid black;width:190px;">
                            <tr>
                                <td style="border:1px solid black;">Mold name</td>
                                <td style="border:1px solid black;">'.$mold->moldname.'</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid black;">Asset No.</td>
                                <td style="border:1px solid black;">'.$mold->asset_no.'</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid black;">Model</td>
                                <td style="border:1px solid black;">'.$mold->model.'</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid black;">Part no.</td>
                                <td style="border:1px solid black;">'.$mold->partnumber.'</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid black;">Actual shot</td>
                                <td style="border:1px solid black;">'.$actual.'</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid black;">Std Shot PM</td>
                                <td style="border:1px solid black;">'.$mold->std_shot.'</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid black;">status</td>
                                <td style="border:1px solid black;">'.$status.'</td>
                            </tr>
                        
                        </table>';
            $mail->Body    = $bodyhtml;
            
            if(!$mail->Send()) {
                
    
                
                echo 'Message could not be sents.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;

                $log  = date("j.n.Y H:i:s")."\r\n"."ERROR: ".$mail->ErrorInfo."\r\n".
                        "-------------------------"."\r\n\r\n";
                
                file_put_contents('./logfile/log_ERROR_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
                exit;
            }else{
                echo 2;
                $log  = date("j.n.Y H:i:s")."\r\n"."SUCCESS: Mail Send ".$part_no."\r\n".
                "-------------------------"."\r\n\r\n";
        
                file_put_contents('./logfile/log_SUCCESS_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
                //return json_encode("1");
            }
        } catch (Exception $e) {
            echo 500;
            $log  = date("j.n.Y H:i:s")."\r\n"."Exception: ".$e->getMessage()."\r\n".
                        "-------------------------"."\r\n\r\n";
                
            file_put_contents('./logfile/log_Exception_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
            echo "Message could not be sent. Exception Error: {$e->getMessage()}";
        }
    }
}
