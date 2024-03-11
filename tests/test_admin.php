<?php

$username = 'root';
$password = 'root';
$database = 'local';

// Attempt to connect to MySQL server
$conn = mysqli_connect("localhost:10012", $username, $password, $database);

// Check connection
if ($conn) {
    error_log('Connected to MySQL server successfully');
} else {
    error_log('Could not connect to MySQL server');
}

global $wpdb;
// Here is an issue with the database connection.
 require_once('C:/Users/ACER/Local Sites/learndash/app/public/wp-load.php');
 var_dump($wpdb);
// Providing the full path of the admin.php file because the short path (../includes/admin/admin.php) is not working.
 require_once('C:/Users/ACER/Local Sites/learndash/app/public/wp-content/plugins/custom/includes/admin/admin.php');
 
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
  
  public function testRenderQuizQuestionsTable() {

    // Test with valid quiz ID
    $quiz_id = 123;

    ob_start();
    render_quiz_questions_table($quiz_id);
    $output = ob_get_clean();

    $this->assertStringContainsString('dataTable', $output);

    // Test with invalid quiz ID
    $quiz_id = null;
    ob_start();
    render_quiz_questions_table($quiz_id);
    $output = ob_get_clean();

    $this->assertStringContainsString('Quiz ID is not set.', $output);

  }

}

?>
