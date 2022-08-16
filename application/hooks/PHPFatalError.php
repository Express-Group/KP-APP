<?php
class PHPFatalError {

public function setHandler() {
        register_shutdown_function('handleShutdown');
    }
}
function handleShutdown() {
    if (($error = error_get_last())) {
		ob_get_clean();
        ob_start();
       echo json_encode($error);
        ob_flush();
        exit();
    }
}
?>