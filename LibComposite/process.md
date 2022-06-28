## Requirements
1. Raspbian OS (Lite or Full - both is fine)

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

#!/bin/bash
cd /sys/kernel/config/usb_gadget/
mkdir -p composite_usb
cd composite_usb
echo 0x1d6b > idVendor # Linux Foundation
echo 0x0104 > idProduct # Multifunction Composite Gadget
echo 0x0100 > bcdDevice # v1.0.0
echo 0x0200 > bcdUSB # USB2
mkdir -p strings/0x409
echo "1234567890" > strings/0x409/serialnumber
echo "danieltanzhonghao" > strings/0x409/manufacturer
echo "ITP24 Composite USB Device" > strings/0x409/product
mkdir -p configs/c.1/strings/0x409
echo "Config 1: ECM network" > configs/c.1/strings/0x409/configuration
echo 250 > configs/c.1/MaxPower
# Gadget functions will be added here

# End of gadget functions
ls /sys/class/udc > UDC
```
3. Adding script to rc.local so that it will run on boot everytime
Add line to before **exit 0**!!
```
/usr/bin/composite_usb
```
## Step 4: Configure all the desired USB Gadgets 
