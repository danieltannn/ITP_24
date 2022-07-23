import base64, os, rsa
from cryptography.fernet import Fernet
from getmac import get_mac_address as gma

# variables
TRIGGER = 0
UPDATEINTERVAL = False
blacklist = ['telegram', 'whatsapp', 'spotify']

# function to decode base64
def decodebase64(base64_message):
    # convert the base64 message into bytes
    base64_bytes = base64_message.encode('utf-16le')
    # decode the bytes from the base64 message
    message_bytes = base64.b64decode(base64_bytes)
    # convert the decoded message into a readable string
    message = message_bytes.decode('utf-16le')
    return message

def encodebase64(message):
    # convert the base64 message into bytes
    message_bytes = message.encode('ascii')
    # decode the bytes from the base64 message
    base64_bytes = base64.b64encode(message_bytes)
    # convert the decoded message into a readable string
    base64_message = base64_bytes.decode('ascii')
    return base64_message

# function to process the data received from the Student's PC
def processing(data, category):
    global TRIGGER, UPDATEINTERVAL
    try:
        if category == 'AWD':
            if data in blacklist:
                TRIGGER += 1
                UPDATEINTERVAL = True
        if category == 'AMD':
            if int(data) > 4:
                TRIGGER += 1
                UPDATEINTERVAL = True
        if category == 'PL' or category == 'OW':
            if any(element in data for element in blacklist):
                TRIGGER += 1
                UPDATEINTERVAL = True
    except Exception as e:
        print(e)

# Constructing JSON response, pending encryption integration
def constructDataResponse(data, category, key):
    global TRIGGER, UPDATEINTERVAL
    response = {}

    # encrypt data and add to response dictionary
    # "1" : Number of times student has been "flagged"/ triggered, encrypted with Fernet
    response["1"] = encrypt_text(str(TRIGGER), key)
    # "2" : Flag to dynamically update the frequency data is sent by the Student PC, encrypted with Fernet
    response["2"] = encrypt_text(str(UPDATEINTERVAL), key)
    # "3" : The category of the data received, encrypted with Fernet i.e OW = List of opened windows
    response["3"] = encrypt_text(category, key)

    # check for category where data is a list
    # "4" : data that was received from the Student's PC, encrypted with Fernet
    if category in ['OW', 'PL']:
        data_list = []
        # encrypt each item in the list
        for item in data:
            data_list.append(encrypt_text(item, key))
        response["4"] = data_list
    else:
        response["4"] = encrypt_text(data, key)
    # "5" : Symmetric key used to encrypt data, encrypted using RSA and invigilator portal's public key
    response["5"] = encrypt_key(key)
    # "6" : MAC address of device, encrypted with Fernet
    response["6"] = encrypt_text(gma(), key)
    
    # reset flag
    UPDATEINTERVAL = False
    return response

def constructMacResponse():
    response = {}
    response["UUID"] = encodebase64(gma())
    return response

# Symmetric Encryption
def gen_key():
    key = Fernet.generate_key()
    return key

def store_public_key(key):
    global PUBLICKEY
    # base64 decode public key received from the server
    decodedKey = decodebase64(key)
    # convert the string from payload into an RSA key and store it in the global variable
    PUBLICKEY = rsa.PublicKey.load_pkcs1_openssl_pem(decodedKey)
    return

def encrypt_text(plaintext, key):
    # convert the text to bytes
    encodedtext = plaintext.encode('utf-8')
    fernet = Fernet(key)
    # encrypt the bytes using fernet
    ciphertext = fernet.encrypt(encodedtext)
    # convert the ciphertext into a readable string and return
    return ciphertext.decode('utf-8')

def encrypt_key(key):
    global PUBLICKEY
    # encrypt the key using RSA and the public key of the invigilator portal
    encryptedkey = rsa.encrypt(key, PUBLICKEY)
    # encode the result in base64 and convert it into a readable string
    return base64.b64encode(encryptedkey).decode('utf-8')