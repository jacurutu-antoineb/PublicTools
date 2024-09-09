import time

from Functions import Log, Err

def main():
    while True:
        Log("MSFT: Application Activity")
        Err("MSFT: Application Activity")
        time.sleep(10)  # Pause for 10 seconds

if __name__ == "__main__":
    main()
