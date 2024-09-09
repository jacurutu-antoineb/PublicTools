import time

from Functions import Log, Err

def main():
    while True:
        Log("Google: User Activity")
        Err("Google: User Activity")
        time.sleep(10)  # Pause for 10 seconds

if __name__ == "__main__":
    main()
