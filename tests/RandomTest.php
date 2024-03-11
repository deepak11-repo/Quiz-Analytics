<?php

namespace Deepak\Test;

// use Deepak\Custom\admin;

// require_once '../includes/admin/admin.php';

require_once 'C:/Users/ACER/Local Sites/learndash/app/public/wp-load.php';
require_once 'C:/Users/ACER/Local Sites/learndash/app/public/wp-content/plugins/custom/functions.php';


use PHPUnit\Framework\TestCase;

// global $wpdb;

class RandomTest extends TestCase {
    public function testRenderQuizQuestionsTable() {
        // Arrange: Set up any necessary variables or objects
        $quiz_id = 123;
        
        // Act: Call the method being tested
        ob_start();
        render_quiz_questions_table($quiz_id);
        // admin::render_quiz_questions_table($quiz_id);
        $output = ob_get_clean();
        
        // Assert: Check that the output meets expectations
        $this->assertStringContainsString('dataTable', $output);
    } 
}
