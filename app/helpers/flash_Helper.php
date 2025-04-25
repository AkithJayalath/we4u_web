<?php
/**
 * Flash Message Helper
 * Displays notification messages in the top-right corner of the screen
 */

class FlashHelper {
    /**
     * Set a flash message
     * 
     * @param string $type   The type of message (success, error, warning, info)
     * @param string $message The message to display
     * @return void
     */
    public static function set($type, $message) {
        // Initialize the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Store flash message in session
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
            'created' => time()
        ];
    }

    /**
     * Check if a flash message exists
     * 
     * @return bool True if a flash message exists
     */
    public static function has() {
        return isset($_SESSION['flash']);
    }

    /**
     * Get and clear the flash message
     * 
     * @return array|null The flash message or null if none exists
     */
    public static function get() {
        if (self::has()) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Render the flash message HTML and JavaScript
     * 
     * @return string The HTML and JavaScript for the flash message
     */
    public static function render() {
        if (!self::has()) {
            return '';
        }

        $flash = self::get();
        $type = htmlspecialchars($flash['type']);
        $message = htmlspecialchars($flash['message']);
        
        // Define colors for different message types
        $bgColors = [
            'success' => '#9bfaae',
            'error' => '#fa9b9b',
            'warning' => '#fada9b',
            'info' => '#9be8fa'
        ];

        $textColors = [
            'success' => '#0a6600',
            'error' => '#a60702',
            'warning' => '#a66a02',
            'info' => '#024ea6'
        ];
        
        // Use default color if type is not defined
        $bgColor = isset($bgColors[$type]) ? $bgColors[$type] : '#2196f3';
        $textColors = isset($textColors[$type]) ? $textColors[$type] : '#ffffff';
        // HTML and inline CSS for the flash message - made larger
        $html = <<<HTML
        <div id="flash-message" style="
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 20px 25px;
            background-color: {$bgColor};
            color: {$textColors};
            border-radius: 6px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.3);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            max-width: 380px;
            font-family: Arial, sans-serif;
            font-size: 18px;
            font-weight: 500;
            line-height: 1.4;
        ">
            {$message}
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const flashMessage = document.getElementById('flash-message');
                
                // Show the message with a fade-in effect
                setTimeout(function() {
                    flashMessage.style.opacity = '1';
                }, 100);
                
                // Hide the message after 5 seconds
                setTimeout(function() {
                    flashMessage.style.opacity = '0';
                    
                    // Remove the element from DOM after fade out
                    setTimeout(function() {
                        if (flashMessage && flashMessage.parentNode) {
                            flashMessage.parentNode.removeChild(flashMessage);
                        }
                    }, 300);
                }, 5000);
            });
        </script>
        HTML;
        
        return $html;
    }
}

/**
 * Helper function for setting flash messages
 * 
 * @param string $type    The type of message (success, error, warning, info)
 * @param string $message The message to display
 * @return void
 */
function flash($type, $message) {
    FlashHelper::set($type, $message);
}

// For success messages
//flash('success', 'Request cancelled successfully.');

// For error messages
//flash('error', 'An error occurred.');

// For warning messages
//flash('warning', 'Please check your information.');

// For information messages
//flash('info', 'Your appointment is scheduled.');
?>