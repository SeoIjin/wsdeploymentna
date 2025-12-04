<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendApprovalEmail($recipientEmail, $recipientName) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shouballesteros4@gmail.com'; // CHANGE THIS: Your Gmail
        $mail->Password   = 'ozjfbdrhapodiwuo';     // CHANGE THIS: Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Barangay 170 Admin');
        $mail->addAddress($recipientEmail, $recipientName);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Approved - Barangay 170 BCDRS';
        
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #228650; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                .button { display: inline-block; padding: 12px 30px; background: #228650; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Account Approved!</h1>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$recipientName}</strong>,</p>
                    
                    <p>Congratulations! Your account registration for <strong>Barangay 170 Citizen Document Request System (BCDRS)</strong> has been approved.</p>
                    
                    <p>You can now log in and access all available services:</p>
                    <ul>
                        <li>Submit document requests</li>
                        <li>Track your applications</li>
                        <li>View request status updates</li>
                        <li>Access community announcements</li>
                    </ul>
                    
                    <div style='text-align: center;'>
                        <a href='http://localhost/your-project/sign-in.php' class='button'>Login to Your Account</a>
                    </div>
                    
                    <p><strong>Important Reminders:</strong></p>
                    <ul>
                        <li>Keep your login credentials secure</li>
                        <li>Update your profile information as needed</li>
                        <li>Check your email regularly for updates on your requests</li>
                    </ul>
                    
                    <p>If you have any questions or need assistance, please contact us at the barangay office.</p>
                    
                    <p>Thank you for using our service!</p>
                    
                    <p>Best regards,<br>
                    <strong>Barangay 170 Administration</strong><br>
                    Deparo, Caloocan City</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; 2025 Barangay 170. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $mail->AltBody = "Dear {$recipientName},\n\nCongratulations! Your account for Barangay 170 BCDRS has been approved. You can now log in and access all available services.\n\nBest regards,\nBarangay 170 Administration";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
}

function sendRejectionEmail($recipientEmail, $recipientName, $rejectionReason = 'verification requirements not met') {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shouballesteros4@gmail.com'; // CHANGE THIS: Your Gmail
        $mail->Password   = 'ozjfbdrhapodiwuo';     // CHANGE THIS: Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Barangay 170 Admin');
        $mail->addAddress($recipientEmail, $recipientName);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Registration Update - Barangay 170 BCDRS';
        
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                .reason-box { background: #fee2e2; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0; }
                .button { display: inline-block; padding: 12px 30px; background: #228650; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Account Registration Update</h1>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$recipientName}</strong>,</p>
                    
                    <p>Thank you for your interest in registering for the <strong>Barangay 170 Citizen Document Request System (BCDRS)</strong>.</p>
                    
                    <p>After careful review, we regret to inform you that your account registration could not be approved at this time.</p>
                    
                    <div class='reason-box'>
                        <strong>Reason:</strong> {$rejectionReason}
                    </div>
                    
                    <p><strong>What you can do:</strong></p>
                    <ul>
                        <li>Review the registration requirements</li>
                        <li>Ensure all required documents are valid and clearly visible</li>
                        <li>Submit a new registration with corrected information</li>
                        <li>Visit the barangay office for assistance</li>
                    </ul>
                    
                    <div style='text-align: center;'>
                        <a href='http://localhost/your-project/sign-up.php' class='button'>Register Again</a>
                    </div>
                    
                    <p><strong>Need Help?</strong></p>
                    <p>If you believe this decision was made in error or need clarification, please visit our office during business hours (Mon-Fri, 8:00 AM - 5:00 PM) or contact us at:</p>
                    <ul>
                        <li>Email: K1contrerascris@gmail.com</li>
                        <li>Phone: (02) 8123-4567</li>
                    </ul>
                    
                    <p>We appreciate your understanding.</p>
                    
                    <p>Best regards,<br>
                    <strong>Barangay 170 Administration</strong><br>
                    Deparo, Caloocan City</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; 2025 Barangay 170. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $mail->AltBody = "Dear {$recipientName},\n\nThank you for your interest in registering for Barangay 170 BCDRS. After review, we regret to inform you that your account registration could not be approved at this time.\n\nReason: {$rejectionReason}\n\nYou may submit a new registration with the correct information or visit our office for assistance.\n\nBest regards,\nBarangay 170 Administration";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
}
?>