import subprocess
import time
import os

php_script = "chingutell-ussd.php"
lock_file = "php_script.lock"

def is_php_script_running():
    # Check if the lock file exists (indicating the PHP script is running)
    return os.path.exists(lock_file)

def run_php_script():
    while True:
        if not is_php_script_running():
            try:
                # Create the lock file
                open(lock_file, "w").close()

                subprocess.check_call(["php", php_script])

            except Exception as e:
                print(f"Error running PHP script: {e}")

            finally:
                # Remove the lock file when the PHP script finishes
                os.remove(lock_file)
        time.sleep(5)  # Sleep for a few seconds before checking again

if __name__ == "__main__":
    run_php_script()

