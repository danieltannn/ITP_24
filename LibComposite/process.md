# Section A: Requirements
1. Raspbian OS (Lite or Full - both is fine)

# Section B: Initial Setup 
## Step 1: Configure the SD Card 
1. Flash Raspbian OS into SD card using Raspberry Pi Imager
   - To allow a headless setup, click on the gear icon on the bottom right and enable ssh while filling in your wifi details.
2. SSH into the Raspberry Pi Zero W

## Step 2: Configuring the Kernel 
1. Enabling device tree overlay
```
echo "dtoverlay=dwc2" | sudo tee -a /boot/config.txt
echo "dwc2" | sudo tee -a /etc/modules
```
2. Enabling the libcomposite module
```
sudo echo "libcomposite" | sudo tee -a /etc/modules
```
## Step 3: Configuring the gadget
1. Create a config script and make it executable
```
sudo touch /usr/bin/composite_usb
sudo chmod +x /usr/bin/composite_usb
```
2. Editting the config script:
```
sudo nano /usr/bin/composite_usb
```
```
#!/bin/bash
cd /sys/kernel/config/usb_gadget/

# Creating a gadget directory for gadget configuration
mkdir -p compositeusb
cd composite_usb

# configure gadget details
# =========================
echo 0x1d6b > idVendor # Linux Foundation
echo 0x0104 > idProduct # Multifunction Composite Gadget
echo 0x0100 > bcdDevice # v1.0.0
echo 0x0200 > bcdUSB # USB2

# Configure the text strings
# ===========================
mkdir -p strings/0x409
echo "1234567890" > strings/0x409/serialnumber
echo "danieltanzhonghao" > strings/0x409/manufacturer
echo "ITP24 Composite USB Device" > strings/0x409/product

# Initial device configuration
# ============================
mkdir -p configs/c.1/strings/0x409
echo "Config 1: ECM network" > configs/c.1/strings/0x409/configuration
echo 250 > configs/c.1/MaxPower

# Gadget functions will be added here
# 
#  
#
# End of gadget functions

ls /sys/class/udc > UDC
```
3. Adding script to rc.local so that it will run on boot everytime  
Add line to before **exit 0**!!
```
/usr/bin/compositeusb
```
# Section C: Gadgets
The codes in this section will go into /usr/bin/compositeusb file in the raspberry pi 
## 1. Ethernet Gadget
```
#mkdir -p functions/ecm.usb0 #Linux/MAC
mkdir -p functions/rndis.usb0 #windows
# First byte of MAC address must be even
echo "48:6f:73:74:50:43" > functions/ecm.usb0/host_addr # MAC address for HOST PC
echo "42:61:64:55:53:42" > functions/ecm.usb0/dev_addr # MAC address for Pi
ln -s functions/rndis.usb0 configs/c.1/
```
For the below code place it below the last line of the /usr/bin/compositeusb file  
This is to assigned a fixed ip for the raspberry pi.
```
ifconfig usb0 10.0.0.1 netmask 255.255.255.0 up
```
There are three address ranges reserved for private networks:  
• 10.0.0.0 – 10.255.255.255  
• 172.16.0.0 – 172.31.255.255  
• 192.168.0.0 – 192.168.255.255  
To avoid problems with routing and address conflicts use one of these and stay away from the ranges used by any other interfaces on either the HOST PC or the Pi Zero W. For example, if your router uses 192.168.x.x, use 10.x.x.x for the USB link.  
