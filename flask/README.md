# Setting up Flask in Raspberry Pi
reference: https://singleboardbytes.com/1002/running-flask-nginx-raspberry-pi.htm

## Installing dependencies:
```
sudo apt update
sudo apt install nginx python3-pip build-essential python-dev
sudo pip install flask uwsgi
```

## Creating directory for flask application
```
mkdir flaskserver
sudo chown www-data ./flaskserver
cd flaskserver
```
Create a python file for the server code or transfer the `server.py` file from this repository.
```
sudo nano server.py
```

## Testing uWSGI
In the RPi run, `uwsgi --socket 0.0.0.0:8000 --protocol=http -w server:app`

Go to the IP address of the RPi at port 8000 (`<ip>:8000`) to access the flask website

`CTRL+C` to exit the uWSGI test.

## Init file for uWSGI
Create an initialisation file for uWSGI
```
sudo nano uwsgi.ini
```
In the `.ini` file, paste the following:
```
[uwsgi]
chdir = /home/pi/flaskserver
module = server:app

master = true
processes = 1x
threads = 2

uid = www-data
gid = www-data

socket = /tmp/flaskserver.sock
chmod-socket = 664
vacuum = true

die-on-term = true
```

### Check ini file
```
uwsgi --ini uwsgi.ini
```
In a new ssh window, type `ls /tmp` to check whether flaskserver.sock is created

## Nginx to use uWSGI
Remove the default site in nginx.
```
sudo rm /etc/nginx/sites-enabled/default
```
Create a proxy for the flaskserver.
```
sudo nano /etc/nginx/sites-available/flaskserver_proxy
```
In the proxy file, paste the following:
```
server {
  listen 80;
  server_name localhost;

  location / { try_files $uri @app; }
  location @app {
    include uwsgi_params;
    uwsgi_pass unix:/tmp/flaskserver.sock;
  }
}
```
Create a symbolic link to the flaskserver proxy in the `sites-enabled` sub-directory and then restart the NginX service.
```
sudo ln -s /etc/nginx/sites-available/flaskserver_proxy /etc/nginx/sites-enabled
sudo systemctl restart nginx
```

## Enable auto-restart for uWSGI
Navigate to the system folder and create a new service file for uWSGI.
```
cd /etc/systemd/system
sudo nano uwsgi.service
```
In the service file, paste the following:
```
[Unit]
Description=uWSGI Service
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/home/pi/flaskserver/
ExecStart=/usr/local/bin/uwsgi --ini /home/pi/flaskserver/uwsgi.ini

[Install]
WantedBy=multi-user.target
```
Reload the daemon and start the service you just created.
```
sudo systemctl daemon-reload
sudo systemctl start uwsgi.service
```
To enable auto-start of the service on reboot, enter the following command:
```
sudo systemctl enable uwsgi.service
```

## Accessing the Flask Website
Once set-up is complete, check if the website is accessible via the IP of the Raspberry Pi. *You do not need to add the port at the back*
