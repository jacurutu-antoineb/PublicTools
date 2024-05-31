# Use an official Python runtime as a parent image
FROM python:3.11-slim

# Set the working directory to /app
WORKDIR /app
ADD posture_api.sdk-3.55.2-py3-none-any.whl /app

# Install any needed packages specified in requirements.txt
RUN pip install flask
RUN pip install jwt
RUN pip install ./posture_api.sdk-3.55.2-py3-none-any.whl

# Copy the current directory contents into the container at /app
ADD . /app

# Make port 80 available to the world outside this container
EXPOSE 5000

# Run app.py when the container launches
CMD ["python", "app.py"]


