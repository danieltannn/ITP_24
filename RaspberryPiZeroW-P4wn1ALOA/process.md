# Build script source origin
https://github.com/lgeekjopt/P4wnP1_aloa/blob/master/build_support/rpi0w-nexmon-p4wnp1-aloa.sh

# Machine used to build script on: Kali Linux
OS: Kali GNU/Linux Rolling  
Kernel: Linux 5.16.0-kali7-amd64  
Architecture: x86-64  

# Build Process
```
git clone https://github.com/lgeekjopt/P4wnP1_aloa.git clear
cd P4wnP1_aloa/build_support
sudo ./rpi0w-nexmon-p4wnp1-aloa.sh 1.0 (1.0 is the version number)
```

# Troubleshooting Process
## Error 1: Missing files (Error Line 215 & 216 & 220)
**Files missing**: 50-bluetooth-hci-auto-poweron.rules & pi-bluetooth+re4son_2.2_all.deb & config.txt 
Logs for error can be found on: error_log_1
**Solution**: Downloaded required file from and saving it to /misc/pi-bluetooth and /misc
1. https://gitlab.com/kalilinux/build-scripts/kali-arm/-/tree/master/bsp/bluetooth/rpi (50-bluetooth-hci-auto-poweron.rules & pi-bluetooth+re4son_2.2_all.deb)
2. https://gitlab.com/kalilinux/build-scripts/kali-arm/-/tree/master/bsp/firmware/rpi (config.txt)

## Error 2: Packages not installed due to bash script using python2 package
``` 
extras="autossh avahi-daemon bash-completion dhcpcd5 dnsmasq dosfstools genisoimage golang haveged hostapd i2c-tools iodine policykit-1 python-configobj python-dev python-pip python-requests wpasupplicant"
```
Error message:
```
Package python-dev is not available, but is referred to by another package.
This may mean that the package is missing, has been obsoleted, or
is only available from another source
However the following packages replace it:
  python2-dev python2 python-dev-is-python3

E: Unable to locate package python-configobj
E: Package 'python-dev' has no installation candidate
E: Unable to locate package python-requests
```
This had thus caused other packages to not be installed which lead to the cause of error as shown in log_error_2.  
**Solution**: Editing packages to utilise python3 
```
extras="autossh avahi-daemon bash-completion dhcpcd5 dnsmasq dosfstools genisoimage golang haveged hostapd i2c-tools iodine policykit-1 python3-configobj python3-dev python3-pip python3-requests python3-smbus wpasupplicant"
```

## Error 3: Missing sudo package
sudo package was not installed.
**Solution**: Including sudo package into list of packages to be installed 
```
base="apt-transport-https apt-utils console-setup e2fsprogs firmware-linux firmware-realtek firmware-atheros ifupdown initramfs-tools iw kali-defaults man-db mlocate netcat-traditional net-tools parted psmisc rfkill screen snmpd snmp sudo tftp tmux unrar usbutils vim wget zerofree"
```

## Error 4: Redundant yyloc global declaration
**Solution**: Remove redundant declaration 
```
patch -p1 --no-backup-if-mismatch < ${base_dir}/../patches/11647f99b4de6bc460e106e876f72fc7af3e54a6.patch
```
file downloaded from https://gitlab.com/kalilinux/build-scripts/kali-arm/-/tree/master/patches. Saved to /patches
