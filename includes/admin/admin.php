<?php
add_action('admin_menu', 'add_quiz_analytics_submenu_to_learndash');

function add_quiz_analytics_submenu_to_learndash() {
    add_submenu_page(
        'learndash-lms',                   // Parent menu slug (LearnDash LMS)
        'Quiz Analytics',                  // Page title
        'Quiz Analytics 🔵🟢',             // Menu title
        'manage_options',                  // Capability required
        'quiz-analytics',                  // Menu slug
        'quiz_analytics_page_content'       // Function to render page content
    );
}

function quiz_analytics_page_content() {
    ?>
    <div class="wrap">        
        <div class="introContainer">
            <div>
                <h1>Quiz Analytics</h1>
                <p>Welcome to the Quiz Analytics page. Here you can view insights into your quizzes.</p>
            </div>
            <div>
                <svg height="80px" style="enable-background:new 0 0 110 110;" version="1.0" viewBox="0 0 110 110" width="110px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Artboard"/><g id="Multicolor"><circle cx="55" cy="55" r="55" style="fill:#32BEA6;"/><g><rect height="60" style="fill:#FFFFFF;" width="50" x="37" y="27"/><g><path d="M65.724,73c0,4.418,3.582,8,8,8c4.418,0,8.416-3.601,8-8c-0.423-4.476-3.893-8.042-8-8     C69.306,65.046,65.724,68.582,65.724,73z" style="fill:#FBE158;"/><path d="M65.724,73c0,4.42,3.58,8,8,8c3.36,0,6.233-2.067,7.42-5l-14.78-6.153     C65.951,70.813,65.724,71.88,65.724,73z" style="fill:#059BBF;"/><path d="M65.712,73.003c0,2.82,1.46,5.3,3.667,6.727l4.327-6.82l-7.353-3.06     C65.939,70.817,65.712,71.883,65.712,73.003z" style="fill:#4AC3AF;"/><path d="M66.364,69.847l7.36,3.067V65C70.424,65,67.591,67,66.364,69.847z" style="fill:#FA5655;"/></g><rect height="18" style="fill:#4AC3AF;" width="4" x="77" y="40"/><rect height="10" style="fill:#4AC3AF;" width="4" x="69.712" y="48"/><g><circle cx="60.333" cy="68.167" r="1.5" style="fill:#CFD3D4;"/><rect height="1" style="fill:#CFD3D4;" width="10" x="46.712" y="67.667"/></g><g><circle cx="60.333" cy="73.333" r="1.5" style="fill:#CFD3D4;"/><rect height="1" style="fill:#CFD3D4;" width="10" x="46.712" y="72.833"/></g><g><circle cx="60.333" cy="78.5" r="1.5" style="fill:#CFD3D4;"/><rect height="1" style="fill:#CFD3D4;" width="10" x="46.712" y="78"/></g><path d="M39.742,58.021L28.944,68.819c-1.344,1.344-3.522,1.344-4.867,0s-1.344-3.522,0-4.867L34.9,53.131    C36.098,55.126,37.758,56.805,39.742,58.021z" style="fill:#84462D;"/><path d="M36.992,55.826L24.041,68.777c-1.308-1.344-1.296-3.492,0.03-4.824L34.9,53.125    C35.489,54.104,36.195,55.01,36.992,55.826z" style="fill:#9C6144;"/><path d="M47.4,60.1c-7.995,0-14.5-6.505-14.5-14.5s6.505-14.5,14.5-14.5s14.5,6.505,14.5,14.5    S55.396,60.1,47.4,60.1z" style="fill:#FFFFFF;"/><path d="M47.4,31.088c8.012,0,14.512,6.5,14.512,14.512s-6.5,14.512-14.512,14.512    c-8.041,0-14.512-6.561-14.512-14.512C32.888,37.588,39.388,31.088,47.4,31.088 M47.4,29.1c-9.098,0-16.5,7.402-16.5,16.5    s7.402,16.5,16.5,16.5s16.5-7.402,16.5-16.5S56.499,29.1,47.4,29.1L47.4,29.1z" style="fill:#CFD3D4;"/><circle cx="47.4" cy="45.6" r="11.558" style="fill:#6FDAF1;"/><path d="M47.4,34.042c-6.383,0-11.558,5.175-11.558,11.558c0,3.158,1.27,6.018,3.323,8.104l16.339-16.339    C53.418,35.311,50.559,34.042,47.4,34.042z" style="fill:#9CE5F4;"/></g></g></svg>
            </div>
        </div>
        <div id="quiz-content">
        <?php             
            if(isset($_REQUEST['quiz_id'])){
                render_quiz_redirect_page();
            } else {
                render_quiz_analytics_table();
            }
        ?>
        </div>
    </div>
    <?php
}

function render_quiz_analytics_table() {
    global $wpdb;

    // Fetching the number of students enrolled in the course
    $total_studs_enrolled_course_query = "
        SELECT COUNT(ID) AS total_studs_enrolled_course 
        FROM {$wpdb->prefix}users 
        WHERE display_name <> 'admin'
    ";
    $total_studs_enrolled_course = $wpdb->get_var($total_studs_enrolled_course_query);

    // Fetching the number of students attended each quiz
    $num_students_attended_query = "
        SELECT 
            m.id AS quiz_id,
            m.name AS quiz_name,
            COUNT(DISTINCT s.user_id) AS num_students_attended
        FROM 
            {$wpdb->prefix}learndash_pro_quiz_master m
        LEFT JOIN 
            {$wpdb->prefix}learndash_pro_quiz_statistic_ref s ON m.id = s.quiz_id
        GROUP BY 
            m.id, m.name;
    ";
    $num_students_attended_results = $wpdb->get_results($num_students_attended_query);
    $sr_no = 1;
    ?>
    <div id="quiz-table">
        <table id="dataTable" class="quiz-analytics-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Quiz ID</th>
                    <th>Quiz Name</th>
                    <th>No of Students Attended Quiz</th>
                    <th>No of Students Enrolled</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($num_students_attended_results as $result) {
                    ?>
                    <tr>
                        <td><?php echo $sr_no++; ?></td>
                        <td><?php echo $result->quiz_id; ?></td>

                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=quiz-analytics&quiz_id=' . $result->quiz_id)); ?>">
                                <?php echo $result->quiz_name; ?>
                            </a>
                        </td>

                        <td><?php echo $result->num_students_attended; ?></td>
                        <td><?php echo $total_studs_enrolled_course; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php   
}

function render_quiz_redirect_page() {
    ?>
    <div class="redirectPage">        
        <div>
            <div class="tabLinkContainer">   
                <?php
                $quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : '';

                // Remove the "question" parameter if it exists
                $current_url = remove_query_arg('question');

                // Remove the "students" parameter if it exists
                $current_url = remove_query_arg('students', $current_url);

                $question_url = '';
                $students_url = '';

                if (!empty($quiz_id)) {
                    // Append the "question" parameter
                    $question_url = esc_url(add_query_arg('quiz_id', $quiz_id, $current_url . '&question'));

                    // Append the "students" parameter
                    $students_url = esc_url(add_query_arg('quiz_id', $quiz_id, $current_url . '&students'));
                }
                ?>
                
                <a href="<?php echo $question_url; ?>" class="tabLinks  active" onclick="activateTab(this)">Questions</a>
                <span>|</span>
                <a href="<?php echo $students_url; ?>" class="tabLinks" onclick="activateTab(this)">Students</a>
            </div>            
        </div>
        <div>
            <a href="<?php echo esc_url(admin_url('admin.php?page=quiz-analytics')); ?>" class="backBtn">Back</a>
        </div>
    </div>
    
    <div>
        <!-- Display the table here -->
        <?php             
        if (isset($_REQUEST['quiz_id']) && isset($_REQUEST['students'])) {                
            render_students_filter($quiz_id);
        } else {
            render_quiz_questions_table($quiz_id);
        }
        ?>
    </div>
    <?php
}

function render_quiz_questions_table($quiz_id) {
    ?>
    <div id="questions-table">
    <?php
    if (!isset($quiz_id)) {
        echo "Quiz ID is not set.";
        return;
    }
    
    global $wpdb;
    
    $quiz_data_query = "
        SELECT
            ref.user_id,
            stat.question_id,
            q.question AS title,
            q.question AS question,
            stat.correct_count,
            stat.incorrect_count,
            stat.answer_data
        FROM
            {$wpdb->prefix}learndash_pro_quiz_statistic_ref AS ref
        JOIN {$wpdb->prefix}learndash_pro_quiz_statistic AS stat ON ref.statistic_ref_id = stat.statistic_ref_id
        JOIN {$wpdb->prefix}learndash_pro_quiz_question AS q ON stat.question_id = q.id
        WHERE ref.quiz_id = $quiz_id
    ";
    
    $quiz_data = $wpdb->get_results($quiz_data_query);

    $quiz_arr = array();
    foreach($quiz_data as $quiz_attempt) {
        $options = json_decode($quiz_attempt->answer_data);
        $temp = isset($quiz_arr[$quiz_attempt->question_id]['option']) ? $quiz_arr[$quiz_attempt->question_id]['option'] : array();
        foreach($options as $key => $option) {
            if($option == 1){
                $temp[$key] = isset($quiz_arr[$quiz_attempt->question_id]['option'][$key]) ? $quiz_arr[$quiz_attempt->question_id]['option'][$key] + 1  : 1;
            }
        }
        $quiz_arr[$quiz_attempt->question_id] = array(
            'question' => $quiz_attempt->title,
            'option' => $temp,
        );
    }
    
    $query_attended = "
        SELECT COUNT(DISTINCT ua.user_id) AS num_students_attended
        FROM {$wpdb->prefix}learndash_user_activity ua
        JOIN {$wpdb->prefix}users u ON ua.user_id = u.ID
        WHERE ua.activity_type = 'quiz'
        AND u.display_name <> 'admin';
    ";
    
    $num_students_attended = $wpdb->get_var($query_attended);
    ?>
    <table id="dataTable">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Questions</th>
                <?php
                $optionCount = count(json_decode($quiz_data[0]->answer_data)); 
                for ($i = 0; $i < $optionCount; $i++) {
                    echo '<th>Option ' . chr(65 + $i) . '</th>';    
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $serial_number = 1;
            foreach ($quiz_arr as $question_id => $question_data) {
                echo '<tr>';
                echo '<td>' . $serial_number . '</td>'; 
                echo '<td>' . $question_data['question'] . '</td>';
                foreach (range(0, $optionCount - 1) as $optionIndex) {
                    $user_count = isset($question_data['option'][$optionIndex]) ? $question_data['option'][$optionIndex] : 0;
                    // Calculate percentage
                    $percentage = ($num_students_attended > 0) ? round(($user_count / $num_students_attended) * 100, 2) : 0;
                    echo '<td>' . $user_count . ' (' . $percentage . '%)</td>'; 
                }
                echo '</tr>';
                $serial_number++;
            }
            ?>
        </tbody>
    </table>
    </div>  
    <?php
}

function render_students_filter($quiz_id) {
    ?>
    <div id="students-filter" class="filter-by-students">
        <?php    
        global $wpdb;
        $students_query = "
        SELECT DISTINCT ua.user_id, u.display_name
        FROM {$wpdb->prefix}learndash_user_activity ua
        JOIN {$wpdb->prefix}users u ON ua.user_id = u.ID
        WHERE ua.activity_type = 'quiz'
        AND u.display_name <> 'admin'
        ";
        // Fetch results from the database
        $students_data = $wpdb->get_results($students_query);
        ?>
        <label for="student-filter" class="fiterLabel">Filter by students:</label>
        <select id="student-filter" name="student-filter">
            <?php foreach ($students_data as $student): ?>
                <option value="<?php echo $student->user_id; ?>"><?php echo $student->display_name; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="quiz-id" name="quiz-id" value="<?php echo $quiz_id; ?>" />
        <button type="button" id="apply-filter-btn">Apply Filter</button> 
        <div class="tableContainer">
            <table id="studentTable"></table>
        </div>
    </div>
    <?php
}

add_action('wp_ajax_fetch_student_data', 'get_students_filter_questions');
add_action('wp_ajax_nopriv_fetch_student_data', 'get_students_filter_questions');
function get_students_filter_questions() {
    
    $nonce = $_GET['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'fetch_student_data_nonce' ) ) {
        wp_send_json_error( 'Nonce verification failed' );
        exit;
    } else {
        error_log('Nonce verification succeeded');
    }

    global $wpdb;
    if (isset($_GET['studentId']) && isset($_GET['quizId'])) {
        $selected_student_id = intval($_GET['studentId']);
        $selected_quiz_id = intval($_GET['quizId']);
        $quiz_data_query = "
            SELECT
                ref.user_id,
                stat.question_id,
                q.question AS title,
                q.question AS question,
                stat.correct_count,
                stat.incorrect_count,
                stat.answer_data
            FROM
                {$wpdb->prefix}learndash_pro_quiz_statistic_ref AS ref
                JOIN {$wpdb->prefix}learndash_pro_quiz_statistic AS stat ON ref.statistic_ref_id = stat.statistic_ref_id
                JOIN {$wpdb->prefix}learndash_pro_quiz_question AS q ON stat.question_id = q.id
            WHERE
                ref.user_id = $selected_student_id AND
                ref.quiz_id = $selected_quiz_id
        ";
        $quiz_data = $wpdb->get_results($quiz_data_query);
        
        // Process the data
        $quiz_arr = array();
        foreach($quiz_data as $quiz_attempt) {
            $options = json_decode($quiz_attempt->answer_data);
            $temp = isset($quiz_arr[$quiz_attempt->question_id]['option']) ? $quiz_arr[$quiz_attempt->question_id]['option'] : array();
            foreach($options as $key => $option) {
                if($option == 1){
                    $temp[$key] = isset($quiz_arr[$quiz_attempt->question_id]['option'][$key]) ? $quiz_arr[$quiz_attempt->question_id]['option'][$key] + 1  : 1;
                }            
            }
            $quiz_arr[$quiz_attempt->question_id] = array(
                'question' => $quiz_attempt->title,
                'option' => $temp,
            );
        }
        
        ob_start(); // Start output buffering
        ?>
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Question Attended</th>
                    <?php if (!empty($quiz_data)) {
                        $optionCount = count(json_decode($quiz_data[0]->answer_data));
                        for ($i = 0; $i < $optionCount; $i++) {
                            echo '<th>Option ' . chr(65 + $i) . '</th>';    
                        }
                    } else {
                        echo '<th>No Options Available</th>';
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                $serial_number = 1;
                foreach ($quiz_arr as $question_id => $question_data) { ?>
                    <tr>
                        <td><?php echo $serial_number; ?></td>
                        <td><?php echo $question_data['question']; ?></td>
                        <?php foreach (range(0, $optionCount - 1) as $optionIndex) {
                            $user_count = isset($question_data['option'][$optionIndex]) ? $question_data['option'][$optionIndex] : 0; ?>
                            <td><?php echo $user_count; ?></td>
                        <?php } ?>
                    </tr>
                    <?php $serial_number++;
                } ?>
            </tbody>
        <?php
        $html_table = ob_get_clean();        
        // Prepare JSON response
        $response_data = array(
            'html_table' => $html_table
        );        
        // Send JSON response
        wp_send_json_success($response_data); 
    } else {
        // Check if both student ID and quiz ID are missing
        if (!isset($_GET['studentId']) && !isset($_GET['quizId'])) {
            wp_send_json_error('Error: Student ID and Quiz ID not provided in $_GET parameters');
        } elseif (!isset($_GET['studentId'])) {
            // Only student ID is missing
            wp_send_json_error('Error: Student ID not provided in $_GET parameter');
        } else {
            // Only quiz ID is missing
            wp_send_json_error('Error: Quiz ID not provided in $_GET parameter');
        }
    } 
}
