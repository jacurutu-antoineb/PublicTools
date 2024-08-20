FROM python:3.7-slim

RUN pip install mysql-connector-python
RUN pip install requests

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
ADD . /app

# Run app.py when the container launches
CMD ["python", "app.py"]
