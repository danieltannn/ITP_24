# Task List
- [x] Send data without exposing on the Flask server's front-end
- [x] Integrate with Symmetric encryption
- [x] Integration with Asymmetric encryption

---

# Setting up Flask in Raspberry Pi
reference: https://singleboardbytes.com/1002/running-flask-nginx-raspberry-pi.htm

## Prequisites
1. RPi is running Raspbian OS
2. Ethernet Gadget mode is enabled on RPi
3. Laptop is able to recognise RPi as an Ethernet Gadget
### Optional
- Static IP for interface usb0 is set

## Installing dependencies:
```
sudo apt update
sudo apt upgrade
sudo apt install nginx python3-pip build-essential python-dev
sudo pip install flask uwsgi cryptography getmac rsa
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
Create another python file for the functions used by the server or transfer the `functions.py` file from this repository
```
sudo nano functions.py
```

## Testing uWSGI
In the RPi run, `uwsgi --socket 0.0.0.0:8000 --protocol=http -w server:app`
![image](https://user-images.githubusercontent.com/44192542/178711309-9a0ca72b-d7f4-423f-a613-fc48b8c9848d.png)

Go to the IP address of the RPi at port 8000 (`<ip>:8000`) to access the flask website
![image](https://user-images.githubusercontent.com/44192542/178711355-45387793-2e5a-477b-903d-26ac616eb494.png)

Use `CTRL+C` to exit the uWSGI test.

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
![image](https://user-images.githubusercontent.com/44192542/178710569-492c9c50-7b16-4afb-a4b0-c745c12043dd.png)

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
![image](https://user-images.githubusercontent.com/44192542/178710717-653a0cd5-75cd-498e-90dd-500abead6e34.png)


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
![image](https://user-images.githubusercontent.com/44192542/178711077-8cfdd059-6798-487c-82d5-abfe3bc32b55.png)

