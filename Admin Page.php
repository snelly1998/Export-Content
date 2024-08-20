<?php
function wp_toolkit_export_menu()
{
    add_menu_page(
        'Toolkit Export',        // Page title
        'Toolkit Export',        // Menu title
        'manage_options',        // Capability
        'toolkit-export',        // Menu slug
        'wp_toolkit_export_page', // Callback function
        'dashicons-download',    // Icon
        20                      // Position
    );
}
add_action('admin_menu', 'wp_toolkit_export_menu');

function wp_toolkit_export_page()
{
    // Check if the export form was submitted
    if (isset($_POST['wp_toolkit_export_csv']) && $_POST['wp_toolkit_export_csv'] == '1') {
        wp_toolkit_export_csv();
    }

    // Check if the delete form was submitted
    if (isset($_POST['wp_toolkit_delete_data']) && $_POST['wp_toolkit_delete_data'] == '1') {
        wp_toolkit_delete_data();
    }
    ?>
    <div class="wrap">
        <h1>Export and Manage Toolkit Tracking Data</h1>
        <form method="post">
            <input type="hidden" name="wp_toolkit_export_csv" value="1" />
            <?php submit_button('Export to CSV'); ?>
        </form>
        <form method="post" style="margin-top: 20px;" onsubmit="return confirmDelete();">
            <input type="hidden" name="wp_toolkit_delete_data" value="1" />
            <?php submit_button('Delete All Data', 'delete'); ?>
            <strong>This cannot be undone</strong>
        </form>
    </div>

    <script type="text/javascript">
        function confirmDelete() {
            return confirm('Are you sure you want to delete all data? This action cannot be undone.');
        }
    </script>
    <?php
}

function wp_toolkit_export_csv() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'toolkit_tracking';
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    if (empty($results)) {
        wp_die('No data available for export.');
    }

    // Set headers to force download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=toolkit_tracking_export.csv');
    ob_end_clean();

    // Open output stream for CSV
    $output = fopen('php://output', 'w');
    
    // Output header row
    fputcsv($output, array_keys($results[0]));

    // Output data rows
    foreach ($results as $row) {
        fputcsv($output, $row);
    }

    fclose($output);

    // End output buffering and send output
    exit();
}

function wp_toolkit_delete_data() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'toolkit_tracking';
    $result = $wpdb->query("DELETE FROM $table_name");

    if ($result === false) {
        wp_die('Error deleting data.');
    } else {
        wp_die('All data has been deleted.', 'Data Deleted', array('response' => 200, 'back_link' => true));
    }
}
?>
