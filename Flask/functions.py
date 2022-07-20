import base64, os, rsa
from cryptography.fernet import Fernet
from getmac import get_mac_address as gma

# variables
TRIGGER = 0
UPDATEINTERVAL = False
blacklist = ['telegram', 'whatsapp', 'spotify']

# function to decode base64
def decodebase64(base64_message):
    base64_bytes = base64_message.encode('ascii')
    message_bytes = base64.b64decode(base64_bytes)
    message = message_bytes.decode('ascii')
    return message

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
def constructResponse(data, category, key):
    global TRIGGER, UPDATEINTERVAL
    response = {}

    # encrypt data and add to response dictionary
    response["1"] = encrypt_text(str(TRIGGER), key)
    response["2"] = encrypt_text(str(UPDATEINTERVAL), key)
    response["3"] = encrypt_text(category, key)
    if category in ['OW', 'PL']:
        data_list = []
        for item in data:
            data_list.append(encrypt_text(item, key))
        response["4"] = data_list
    else:
        response["4"] = encrypt_text(data, key)
    response["5"] = encrypt_key(key)
    response["6"] = encrypt_text(gma(), key)
    
    # update flag
    UPDATEINTERVAL = False
    return response

# Symmetric Encryption
def gen_key():
    key = Fernet.generate_key()
    return key

def store_public_key(key):
    global PUBLICKEY
    PUBLICKEY = rsa.PublicKey.load_pkcs1_openssl_pem(key.encode('utf-8'))
    return

def encrypt_text(plaintext, key):
    encodedtext = plaintext.encode('utf-8')
    fernet = Fernet(key)
    ciphertext = fernet.encrypt(encodedtext)
    return ciphertext.decode('utf-8')

def encrypt_key(key):
    global PUBLICKEY
    encryptedkey = rsa.encrypt(key, PUBLICKEY)
    return base64.b64encode(encryptedkey).decode('utf-8')