import subprocess
import time


# Path to your PHP script
php_script_path = "chingutell-ussd.php"

# Variable to keep track of the PHP script state
php_script_running = False

def run_php_script():
    while True :
        process = subprocess.Popen(["php", php_script_path])
        print("PHP script started.")
        while process.poll() is None:
        # The process is still running
            time.sleep(5)

run_php_script()
