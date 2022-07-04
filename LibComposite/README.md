# This directory shows the how to set up the Raspberry Pi Zero W into a composite gadget. 
Look into "setup.md" for a more deetailed step by step guide on how to set up the Raspberry Pi Zero W.  

## Different gadget modes that Raspberry Pi Zero W allows
## 1. Serial Gadget
Allow users to connect into the Pi through the serial port for troubleshooting purposes/ or a means to access the Pi when network and video is not available.

**1.1 Ways to configure:**
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
**2.1 What does it provide:**
- Provide a network interface to the operating system on the Pi.
- Provide a network interface to the operating system on the USB host.
- Transfer data between these over the USB link.  

**2.2 What does it not provide:**
- Provide drivers to either OS
- Anything at layer 3 or above in the OSI model routing, TCP/IP, DNS, DHCP, etc.  
(No communication from the Pi to devices other than the USB host over the USB link - including the internet)  
(No communication from other devices connected to the same network as the USB host to the Pi over the USB link)  

**2.3 Ways to configure:**
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
      
**Advance Configuration**
1. Giving the Pi Access to your network and the Internet (requires the USB host to be connected to the network and internet)  
   This is done through Network sharing.
   - This would then allow the Pi to communicate with the Internet :D
   
2. Sharing the Pi's Wifi with the USB Host (only works for Raspberry Pi that have built in WiFi board)

## 3. Mass Storage Device
**3.1 What does it provide:**
- Presents a drive, partition, or image file to the USB host

**3.2 What does it not provide:**
- Provide drivers to OS
- Present a directory on the Pi to the USB host
- Present a network share mounted on Pi to the USB host
- Provide safe weitable storage to either the USB host ot the Pi while the other has any access to the backing store. (Simultaneous writing into storage from Pi and USB host may clash and cause errors.)
- Present a USB drive (or contetns to the USB host)
- Allow the USB host to access the file systems (or disc formats) it does not understand.

The mass storage gadget provides a low level block device to the USB host. The host accesses the backing store by requesting reads from/writes to it of N bytes/blocks starting at address A. The OS on the USB host then processes this data to pass the requested file to the application that needs it.The mass storage gadget has no knowledge of which files and directories are being accessed. It also has no knowledge (and no way to find out) when any given file access has completed. Writing is safe if only the writing side has the backing store mounted. It is not safe if both sides have it mounted and are allowing writing nor is it safe if one side has it mounted read/write and the other read only.  

Modern OS use both read and write caching.  

Caches on on the USB host and the zero are not, and cannot be, kept in sync. While the mass storage gadget knows which blocks/bytes have been changed by the USB host it does not, and cannot, know which files or directories those are part of. It also has no knowledge of whether all the data to be written has been sent or if there is more to come. The mass storage gadget is unaware of any changes made by the zero. The USB host is unaware of any changes made by the zero, the OS on the zero is unaware of any changes made by the USB host.  

Because of the above, writing from one or both sides while the other has it mounted will cause problems including:
- Files deleted from one side still being present on the other.
- Files added from one side not being shown on the other.
- Files moved or renamed from one side still showing the old name or location on the other.
- File contents being overwritten.
- General file or file system corruption.

**3.3 The backing store:**

The backing store for the mass storage gadget can be one of the following:
- **3.3.1 An entire device, e.g. /dev/sda**
   - The USB host has access to everything on the device including its boot sector and partition table(s). If exported to the USB host as read/write, the host can trivially destroy the entire contents of the device. Using an entire device with a zero is not recommended as it gives the USB host access to the boot and root partitions, something that is not advisable in most circumstances.
- **3.3.2 A single partition, e.g. /dev/sda1**
   - Using a partition restricts the USB host’s access to just the specified partition. It has no access to other parts of the device including the device’s partition table and boot sector. Because there is no access to the device’s partition table the USB host may see it as a bare, uninitialised drive. Formatting the partition via the OS on the zero will not fix this, the data exposed to the USB host will still lack a partition table. Initialising and formatting the partition from the USB host writes the necessary data to the partition (partition table etc.) but that results in a drive with two partition tables – one in the normal place and one at the start of one of its partitions. Some OS do not cope well with this when reading the drive directly rather than through the mass storage gadget.
- **3.3.3 A file, which will be treated as a block device, e.g. /srv/backing_store** 
   - Using a file restricts access by the USB host to just that file and its contents. A file can be of an arbitrary size, in an arbitrary location, and have an arbitrary name. Disc image files (.img, .iso, etc.) can be passed to the mass storage gadget as is but the USB host must understand the partition information and file systems within in order for them to be of use.

It cannot be:  
- A directory.
- A network share mounted from another computer.

**3.4 Ways to configure:**
1. **g_mass_storage module**
   - Pros: 
     - Easy and takes care of the majority of the set up and configuration.
   - Cons: 
     - Old and deprecated (will be going away at some point).  
     - Only provide ethernet gadget, if you want other gadgets at the same time it is not possible.
     - Not possible to change the backing store without unloading and reloading the module
    
 2. **Using the libcomposite module and configfs**
    - Pros:
      - Gives a finer control over the gadget
      - Allows more than one gadget at the same time (making it a composite usb)
      - When using multiple gadgets they can be of different types
    - Cons:
      - It's more complicated to set up compared to g_mass_storage
      - Everything must be set up manually

## 4. HID Keyboard
Allows Raspberry Pi Zero W to masked as a keyboard and send keystrokes to the Host PC. 

## 5. Others
...
