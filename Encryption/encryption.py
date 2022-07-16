from cryptography.fernet import Fernet
import uuid


def gen_keyname():
    # keyname = str(uuid.uuid4()) + ".key"
    keyname = str(uuid.uuid4())
    return keyname


def gen_key(filename):
    newkey = Fernet.generate_key()
    return newkey


def encrypt_text(plaintext, key):
    encodedtext = plaintext.encode()
    fernet = Fernet(key)
    ciphertext = fernet.encrypt(encodedtext)
    return ciphertext


def decrypt_text(ciphertext, key):
    fernet = Fernet(key)
    plaintext = fernet.decrypt(ciphertext).decode()
    return plaintext


def encrypt_file(filename, key):
    fernet = Fernet(key)
    with open(filename, "rb") as f:
        d = f.read()
    e = fernet.encrypt(d)
    with open(filename, "wb") as f:
        f.write(e)


def decrypt_file(filename, key):
    fernet = Fernet(key)
    with open(filename, "rb") as f:
        e = f.read()
    d = fernet.decrypt(e)
    with open(filename, "wb") as f:
        f.write(d)

