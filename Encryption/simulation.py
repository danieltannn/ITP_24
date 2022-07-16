# Import needed libraries
import encryption as e
import rsaakey as r


'''
+----------------------------+
|  THIS                      |
|        IS                  |
|            A               |
|               SIMULATION   |
+----------------------------+
'''
print("+----------------------------+\n|  THIS                      |\n|        IS                  |\n"
      "|            A               |\n|               SIMULATION   |\n+----------------------------+")
# To generate key pair
# Generate random name for asymmetric keys
print("Generating random name for asymmetric keys")
pukname, pvkname = r.gen_keyname()
# Generate key pair
print("Generating key pair")
r.gen_key_pair(2048, pukname, pvkname)


# To generate data encryption key
# Generate random name for data encryption key
print("Generating random name for data encryption key")
key_name = e.gen_keyname()
# Generate data encryption key
print("Generating data encryption key")
data_key = e.gen_key(key_name)


# To encrypt data encryption key
# Print original sample file content
print("\033[4mOriginal key:\033[0m")
print(data_key)
# Load public key
print("Load key")
puk = r.load_public_key(pukname)
# Encrypting key
print("Encrypting key")
enkey = r.encrypt_key(data_key, puk)
# Print encrypted sample file content
print("\033[4mEncrypted key:\033[0m")
#with open(enkey, "rb") as f:
    #print(f.read())
print(enkey)


# To encrypt file using data encryption key
# Load key
# print("Loading data encryption key")
# key = e.load_key(key_name)
# Sample string
samplestr = "Hello World!"
# Sample file
samplefile = "sample.txt"
# Encryption for String
print("Encrypting data (string)")
cipherstr = e.encrypt_text(samplestr, data_key)
# Print ciphertext
print("\033[4mCiphertext:\033[0m\n" + str(cipherstr))
# Encryption for File
print("Encrypting data (file)")
e.encrypt_file(samplefile, data_key)
# Print encrypted sample file content
print("\033[4mEncrypted file content:\033[0m")
with open(samplefile, "rb") as f:
    print(f.read())


# To decrypt data encryption key
# Load private key
print("Load key")
pvk = r.load_private_key(pvkname)
# Decrypting data encryption key
print("Decrypting key")
dekey = r.decrypt_key(enkey, pvk)
# Print decrypted sample file content
print("\033[4mDecrypted key:\033[0m")
#with open(dekey, "rb") as f:
    #print(f.read())
print(dekey)


# To decrypt data using data encryption key
# Load key
#print("Loading data encryption key")
#key = e.load_key(key_name)
# Decryption for String
print("Decrypting data (string)")
plainstr = e.decrypt_text(cipherstr, dekey)
# Print plaintext
print("\033[4mPlaintext:\033[0m\n" + str(plainstr))
# Decryption for File
print("Decrypting data (file)")
e.decrypt_file(samplefile, dekey)
# Print decrypted sample file content
print("\033[4mDecrypted file content:\033[0m")
with open(samplefile, "rb") as f:
    print(f.read())




