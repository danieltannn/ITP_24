# Section A: Requirements
1. Raspbian OS (Lite or Full - both is fine)

# Section B: Initial Setup 
<details><summary> Click to expand </summary>  
   
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
   cd compositeusb

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
   # =============================
   mkdir -p configs/c.1/strings/0x409
   echo "Config 1: ECM network" > configs/c.1/strings/0x409/configuration
   echo 250 > configs/c.1/MaxPower

   # Gadget functions will be added here
   # ====================================
   # 
   #  
   #
   # End of gadget functions

   ls /sys/class/udc > UDC
   ```
   3. Adding script to rc.local so that it will run on boot everytime  
   Add line to before **exit 0**!!
   ```
   sudo nano /etc/rc.local
   ```
   ```
   /usr/bin/composite_usb
   ```
</details>  

# Section C: Gadgets
<details><summary> The codes in this section will go into the /usr/bin/compositeusb file in the raspberry pi under the header "Gadget functions will be added here". Click to expand </summary>
   
   ## 1. Ethernet Gadget
   ### 1.1a Windows (RNDIS function)
   ```
   mkdir -p functions/rndis.usb0 
   echo "48:6f:73:74:50:43" > functions/rndis.usb0/host_addr # MAC address for HOST PC
   echo "42:61:64:55:53:42" > functions/rndis.usb0/dev_addr # MAC address for Pi
   ln -s functions/rndis.usb0 configs/c.1/
   ```
   Additional configuration is needed if we are configuring Ethernet Gadget mode for Windows. Since Windows does not automatically  install the correct drivers for the Raspberry Pi Zero W. 
   
   To solve this, we have to manually find the device under "Device Manager" and updating its driver to a "RNDIS/Ethernet Device"  
   The .inf file for the driver can be downloaded from this GitHub under LibComposite/RNDIS.inf  
   ### 1.1b Linux/MAC (CDC ECM function)
   ```
   mkdir -p functions/ecm.usb0
   echo "48:6f:73:74:50:43" > functions/ecm.usb0/host_addr # MAC address for HOST PC
   echo "42:61:64:55:53:42" > functions/ecm.usb0/dev_addr # MAC address for Pi
   ln -s functions/ecm.usb0 configs/c.1/
   ```
   ### 1.2 Configuring Static IP address and enabling it in Raspberry Pi Zero W
   For the below code, place it below the last line of the /usr/bin/compositeusb file  
   This is to assigned a fixed ip for the raspberry pi.
   ```
   ifconfig usb0 10.0.0.1 netmask 255.255.255.0 up
   ```
   ### 1.3 Configuring static IP address from HOST PC 
   Assign connection in HOST PC, ipv4 = 10.0.0.2, network mask = 255.255.255.0, default gateway = 10.0.0.2
   ```
   ipconfig #finding the connection in windows
   ifconfig #finding the connection in Linux/MAC
   ```
   
   ### 1.4 Notes
   MAC address can be anything as long as first byte of the address is even  
   As for IP address, you can pick any two IP address from the reserved private networks range (One for the Pi, One for the HOST PC)
   
   ### 1.5 Advance configuration 1: Removing the need to manually install RNDIS driver
   Tricking Windows 10 into auto installing RNDIS driver for a composite gadget so we do not have to manually update its driver.  
   
   To achieve this:
   - Set up an RNDIS gadget using a VID/PID of a known good device that is compatible with composite RNDIS and setting bDeviceClass and bDeviceSubClass to 0x02 for a valid gadget. 
   - Set up the "os_desc" node with Windows.  
   - Link only the RNDIS function to the config (ethernet gadget mode), attach the USB gadget to the device and allow for Windows to detect and install drivers. 
   - Detach the USB gadget to link the rest of my functions such as HID gadget.
   - Setting the bDeviceClass back to 0x00. Forces Windows to use device information in the descriptors and preventing assumption of a particular class.
   - Reattach the USB gadget
   
   The complete example can be seen on bash script: **composite_gadget_autoRNDIS.sh**
   
   ## 2. HID Keyboard
   ```
   mkdir -p functions/hid.usb0
   echo 1 > functions/hid.usb0/protocol
   echo 1 > functions/hid.usb0/subclass
   echo 8 > functions/hid.usb0/report_length
   echo -ne \\x05\\x01\\x09\\x06\\xa1\\x01\\x05\\x07\\x19\\xe0\\x29\\xe7\\x15\\x00\\x25\\x01\\x75\\x01\\x95\\x08\\x81\\x02\\x95\\x01\\x75\\x08\\x81\\x03\\x95\\x05\\x75\\x01\\x05\\x08\\x19\\x01\\x29\\x05\\x91\\x02\\x95\\x01\\x75\\x03\\x91\\x03\\x95\\x06\\x75\\x08\\x15\\x00\\x25\\x65\\x05\\x07\\x19\\x00\\x29\\x65\\x81\\x00\\xc0 > functions/hid.usb0/report_desc
   ln -s functions/hid.usb0 configs/c.1/
   ```
   This will turn the Raspberry Pi Zero W into a HID keyboard however it still requires additional scripts to send keystrokes to the HOST PC (etiher automatically or manually.)
   
</details>
