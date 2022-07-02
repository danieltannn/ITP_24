# This directory shows the how to set up the Raspberry Pi Zero W into a composite gadget. 

## Different gadget modes that Raspberry Pi Zero W allows
## 1. Serial Gadget
Allow users to connect into the Pi through the serial port for troubleshooting purposes/ or a means to access the Pi when network and video is not available.

**Ways to configure:**
1. **g_ether module**
   - Pros: 
     - Easy and takes care of the majority of the set up and configuration.
   - Cons: 
     - Old and deprecated (will be going away at some point).  
     - Only provide serial gadget, if you want other gadgets at the same time it is not possible.
 
 2. **Using the libcomposite module and configfs**
    - Pros:
      - Gives a finer control over the gadget
      - Allows more than one gadget at the same time (making it a composite usb)
      - When using multiple gadgets they can be of different types
    - Cons:
      - It's more complicated to set up compared to g_serial module
      - Everything must be set up manually

## 2. Ethernet Gadget 
**What does it provide:**
- Provide a network interface to the operating system on the Pi.
- Provide a network interface to the operating system on the USB host.
- Transfer data between these over the USB link.  

**What does it not provide:**
- Provide drivers to either OS
- Anything at layer 3 or above in the OSI model routing, TCP/IP, DNS, DHCP, etc.  
(No communication from the Pi to devices other than the USB host over the USB link - including the internet)  
(No communication from other devices connected to the same network as the USB host to the Pi over the USB link)  

**Ways to configure:**
1. **g_ether module**
   - Pros: 
     - Easy and takes care of the majority of the set up and configuration.
   - Cons: 
     - Old and deprecated (will be going away at some point).  
     - Only provide ethernet gadget, if you want other gadgets at the same time it is not possible.
     - On its simplest configuration, it generates random MAC addresses for the interfaces each time it is started (problematic when getting IP addresses froma DHCP server & cause some OS running on USB host to treat it as a new interface on each connection.)
 
 2. **Using the libcomposite module and configfs**
    - Pros:
      - Gives a finer control over the gadget
      - Allows more than one gadget at the same time (making it a composite usb)
      - When using multiple gadgets they can be of different types
    - Cons:
      - It's more complicated to set up compared to g_ether module
      - Everything must be set up manually
      - Some USB hosts may initially report an "Unknown USB device" during boot process and only correct this once gadget configuration is complete (not a huge issue).
      
**Advance Configuration**
1. Giving the Pi Access to your network and the Internet (requires the USB host to be connected to the network and internet)  
   This is done through Network sharing.
   - This would then allow the Pi to communicate with the Internet :D
   
2. Sharing the Pi's Wifi with the USB Host (only works for Raspberry Pi that have built in WiFi board)


