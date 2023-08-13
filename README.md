Welcome to the ContactForm repo. This is a PHP Server Side application which renders an HTML contact form for submission in the browser and then validates and submits the data in the backend to the database. It also sends a mail to the host acknowledging the same.

You can get started with this project using the following steps:

1. Clone the repository to your local machine. Copy it to your htdocs folder if you are using XAMPP.

2. Go to config/database.php file.
   ![Alt text](image.png)

   You may see some text like this.

3. Please edit these to your local or server configurations:

   | CONFIGURATIONS | RELATED SETTINGS                                                                                     |
   | -------------- | ---------------------------------------------------------------------------------------------------- |
   | 'DB_HOST'      | Your localhost or server's host.                                                                     |
   | 'DB_USER'      | You may need to create a user with read and write privilages in your mySQL Database.                 |
   | 'DB_PASS'      | You must provide the password for the above user.                                                    |
   | 'DB_NAME'      | The name of the mySQL database that you may have created. I have set the default name as contact_db. |
   | 'MAIL_TO'      | The email address where you want the email to be sent on successful form submission. |

4. Please note the database should contain a table named contact_form with the following command / columns:

   `CREATE TABLE contact_form (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(18) NOT NULL,
    timestamp VARCHAR(255)
    )`

5. **For the mail to be sent successfully, you must have SMTP setup successfully on your local machine or server failing which there may be a handled exception. This is required as only corePHP is allowed in the project, so
   secure external libraries PHPMailer, etc. could not be used.**

6. The viewable address/url may look something like this on Windows with XAMPP:
   `localhost/ContactForm/`