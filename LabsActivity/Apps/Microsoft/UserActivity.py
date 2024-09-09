import time

from Functions import Log, Err

def main():
    while True:
        Log("MSFT: User Activity")
        Err("MSFT: User Activity")
        time.sleep(10)  # Pause for 10 seconds

if __name__ == "__main__":
    main()
