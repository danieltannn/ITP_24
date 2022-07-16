import encryption as e
import rsaakey as r


'''
+----------------------------+
| SYMMETRIC                  |
|           KEY              |
|               ENCRYPTION   |
+----------------------------+
'''
print("+----------------------------+\n| SYMMETRIC                  |\n|           KEY              |\n"
      "|               ENCRYPTION   |\n+----------------------------+")
# Generate random key name
key_name = e.gen_keyname()
# Generate key
e.gen_key(key_name)
# Load key
key = e.load_key(key_name)
# Sample string
samplestr = "Hello World!"
# Sample file
samplefile = "sample.txt"
# Encryption for String
cipherstr = e.encrypt_text(samplestr, key)
# Print ciphertext
print("\033[4mCiphertext:\033[0m\n" + str(cipherstr))
# Decryption for String
plainstr = e.decrypt_text(cipherstr, key)
# Print plaintext
print("\033[4mPlaintext:\033[0m\n" + str(plainstr))
# Encryption for File
e.encrypt_file(samplefile, key)
# Print encrypted sample file content
print("\033[4mEncrypted file content:\033[0m")
with open(samplefile, "rb") as f:
    print(f.read())
# Decryption for File
e.decrypt_file(samplefile, key)
# Print decrypted sample file content
print("\033[4mDecrypted file content:\033[0m")
with open(samplefile, "rb") as f:
    print(f.read())


'''
+----------------------------+
| ASYMMETRIC                 |
|           KEY              |
|               ENCRYPTION   |
+----------------------------+
'''
print("+----------------------------+\n| ASYMMETRIC                 |\n|           KEY              |\n"
      "|               ENCRYPTION   |\n+----------------------------+")
# Generate random key name
pukname, pvkname = r.gen_keyname()
# Generate key
r.gen_key_pair(2048, pukname, pvkname)
# Load key
puk = r.load_public_key(pukname)
pvk = r.load_private_key(pvkname)
# Print original sample file content
print("\033[4mOriginal key:\033[0m")
with open(key_name, "rb") as f:
    print(f.read())
# Encryption for File
enkey = r.encrypt_key(key_name, puk)
# Print encrypted sample file content
print("\033[4mEncrypted key:\033[0m")
with open(enkey, "rb") as f:
    print(f.read())
# Decryption for File
dekey = r.decrypt_key(enkey, pvk)
# Print decrypted sample file content
print("\033[4mDecrypted key:\033[0m")
with open(dekey, "rb") as f:
    print(f.read())

