import uuid
from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives import serialization, hashes
from cryptography.hazmat.primitives.asymmetric import rsa, padding


# Generate Key Name
def gen_keyname():
    randomname = str(uuid.uuid4())
    pukname = randomname + "puk.pem"
    pvkname = randomname + "pvk.pem"
    return pukname, pvkname


# Generate Key Pairs
def gen_key_pair(keysize, pukfilename, pvkfilename):
    private_key = rsa.generate_private_key(
        public_exponent=65537,
        key_size=keysize,
        backend=default_backend()
    )
    public_key = private_key.public_key()

    pvkpem = private_key.private_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PrivateFormat.PKCS8,
        encryption_algorithm=serialization.NoEncryption()
    )
    with open(pvkfilename, "wb") as f:
        f.write(pvkpem)

    pukpem = public_key.public_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PublicFormat.SubjectPublicKeyInfo
    )
    with open(pukfilename, "wb") as f:
        f.write(pukpem)


def load_private_key(pvkfile):
    with open(pvkfile, "rb") as f:
        private_key = serialization.load_pem_private_key(
            f.read(),
            password=None,
            backend=default_backend()
        )

    return private_key


def load_public_key(pukfile):
    with open(pukfile, "rb") as f:
        public_key = serialization.load_pem_public_key(
            f.read(),
            backend=default_backend()
        )

    return public_key


def encrypt_key(skey, puk):
    #with open(skey, "rb") as f:
        #d = f.read()
    e = puk.encrypt(
        skey,
        padding.OAEP(
            mgf=padding.MGF1(algorithm=hashes.SHA256()),
            algorithm=hashes.SHA256(),
            label=None
        )
    )
    #encrypted_key_file_name = "encrypt" + skey
    #with open(encrypted_key_file_name, "wb") as f:
        #f.write(e)
    return e


def decrypt_key(skey, pvk):
    #with open(skey, "rb") as f:
        #e = f.read()
    d = pvk.decrypt(
        skey,
        padding.OAEP(
            mgf=padding.MGF1(algorithm=hashes.SHA256()),
            algorithm=hashes.SHA256(),
            label=None
        )
    )
    #decrypted_key_file_name = "plain" + skey
    #with open(decrypted_key_file_name, "wb") as f:
        #f.write(d)
    return d

