# 1. Achieving Composite Mode on Raspberry Pi
This section shows the different research done to achieve composite mode on Raspberry Pi Zero W.  
Compose of: P4wnp1, P4wnP1 A.L.O.A, LibComposite Module

Reference materials:
- https://randomnerdtutorials.com/raspberry-pi-zero-usb-keyboard-hid/ (HID Gadget Mode)
- http://www.isticktoit.net/?p=1383 (Configuring Pi)
- https://gist.github.com/Gadgetoid/c52ee2e04f1cd1c0854c3e77360011e2, https://irq5.io/2016/12/22/raspberry-pi-zero-as-multiple-usb-gadgets/ (Configuring auto RNDIS)
- https://github.com/RoganDawes/P4wnP1/blob/master/boot/init_usb.sh (P4wnP1 Libcomposite usage example)

# 2. Conducting Procotring on Student's PC
Shows the research on different ways the Raspberry Pi Zero W can conduct proctoring on the Student's PC.
- Either dropping the proctoring scripts on the Student's PC and executing it
- Or non conventional methods such as fileless execution

Reference materials:
- https://www.mcafee.com/enterprise/en-us/assets/solution-briefs/sb-fileless-malware-execution.pdf
- https://www.varonis.com/blog/fileless-malware#examples
- https://buildmedia.readthedocs.org/media/pdf/pymem/latest/pymem.pdf

# 3. Obfuscation on "Craddle" and Powershell script 
Shows the research on the different tools and methods used:
- To obfuscate the command to retrieve string in file to conduct fileless execution (More on Fileless execution in Section 2. Conducting proctoring on Stuident's PC)
- To obfuscate the procotoring script (which contains the different proctoring functions)

Reference materials:
- https://github.com/danielbohannon/Invoke-Obfuscation
- https://github.com/klezVirus/chameleon
- https://github.com/JoelGMSec/Invoke-Stealth
- https://www.blackhat.com/docs/us-17/thursday/us-17-Bohannon-Revoke-Obfuscation-PowerShell-Obfuscation-Detection-And%20Evasion-Using-Science.pdf

# 4. Flask Server
Shows how to set-up a flask server on the Raspberry Pi that will be used for communication between the student's PC and the RPi

References:
- https://singleboardbytes.com/1002/running-flask-nginx-raspberry-pi.htm

# 5. Encryption
To ensure secure transmission of data and prevent data from being manipulated during transfer, we adopt the hybrid encryption (asymmetric and symmetric encryption used together) for our project.
## 5.1. Asymmetric
RSA is used for the project's public-key encryption as it is one of the widely used public-key cryptosystem and can be easily adopted in our project through existing libraries (i.e. Python's cryptography library).
Encryption Standards and Hash Function
- Rivest-Shamir-Adleman (RSA): RSA is a widely used public-key cryptosystem. It is also one of the oldest cryptosystem.
- SHA256: SHA256 is used together with RSA to make an efficient encryption. This can also be easily implemented with existing libraries.

## 5.2. Symmetric
Fernet is used for the project's symmetric key encryption as it is one of the method available in Python's cryptography library. Fernet also has security features which ensure that data is securely encrypted before transmission.
Encryption Standards and Hash Function
- Advanced Encryption Standard (AES): AES is a symmetric block cipher chosen by the US government to protect classified information
- PKCS #7: One of the Public-Key Cryptography Standards created by RSA Labs for storing signed and/or encrypted data
- HMAC SHA256: Type of keyed hash algo constructed from SHA-256 hash function and used as a Hash-based Message Authentication Code (HMAC)

References:
- https://nitratine.net/blog/post/asymmetric-encryption-and-decryption-in-python/
- https://cryptography.io/en/latest/fernet/
