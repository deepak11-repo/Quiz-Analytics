jQuery(document).ready(function ($) {

  // DataTable initialization
  $("#dataTable").DataTable({
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: "Download Excel",
        filename: "quiz_analytics_report",
        exportOptions: {
          modifier: {
            page: "current",
          },
        },
      },
    ],
  });

  // Function to fetch data based on student ID & quiz ID
  function fetchDataBasedOnStudentId() {
    var studentId = $("#student-filter").val(); 
    var quizId = $("#quiz-id").val(); 
    console.log(studentId);
    console.log(quizId);        

    // Send an AJAX request to fetch data based on the selected student and quiz IDs
    $.ajax({
        url: ajax_object.ajax_url,
        method: "GET",
        data: {
            action: "fetch_student_data", 
            studentId: studentId, 
            quizId: quizId,
            nonce: ajax_object.nonce 
        },
        success: function (response) {
            if (response.success) {
                var htmlTable = response.data.html_table; 
                $("#studentTable").html(htmlTable);
                // Check if DataTables is already initialized on #studentTable
                if (!$.fn.DataTable.isDataTable('#studentTable')) {
                    $("#studentTable").DataTable({
                        dom: "Bfrtip",
                        buttons: [
                            {
                                extend: "excelHtml5",
                                text: "Download Excel",
                                filename: "quiz_analytics_report",
                                exportOptions: {
                                    modifier: {
                                        page: "current",
                                    },
                                },
                            },
                        ],
                    });
                }
            } else {
                console.error("Error: " + response.data);
            }         
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
  }  
  $("#apply-filter-btn").on("click", fetchDataBasedOnStudentId);

  // Function initially to load data based on default selected values
  // fetchDataBasedOnStudentId();

  // Script to display quiz content
  document.addEventListener('DOMContentLoaded', function() {
      var quizContent = document.getElementById('quiz-content');
      quizContent.style.display = 'block'; 
  });
  
  //Active Tab 
  var activeTab = null;
    function activateTab(tab) {
        var tabLinks = document.querySelectorAll('.tabLinks');
        tabLinks.forEach(function(link) {
            link.classList.remove('active');
        });  

        tab.classList.add('active');
        activeTab = tab;
    }
    function applyActiveTab() {
        var tabLinks = document.querySelectorAll('.tabLinks');
        tabLinks.forEach(function(link) {
            if (link.href === window.location.href) {
                activateTab(link);
            }
        });
    }
    applyActiveTab();

});
