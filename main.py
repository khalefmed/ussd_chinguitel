from fastapi import FastAPI
import subprocess
import time
import asyncio

app = FastAPI()

# Path to your PHP script
php_script_path = "chingutell-ussd.php"

async def run_php_script():
    while True :
        process = subprocess.Popen(["php", php_script_path])
        print("PHP script started.")
        while process.poll() is None:
        # The process is still running
            time.sleep(60)

@app.on_event("startup")
async def startup_event():
    # Start the PHP script monitoring as a background task
    asyncio.create_task(run_php_script())

@app.get("/")
async def get_root():
    return {"message": "FastAPI is running."}
