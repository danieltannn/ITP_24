from flask import Flask, jsonify, request
from functions import *

app = Flask(__name__)

HTTP_METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH', 'BREW']

PuK = False

@app.route("/", methods = HTTP_METHODS)
def index():
    global PuK
    """POST - Receive data that was captured by the proctoring script

    Keyword arguments:
    (from the host)
    AWD = Active Windows Detection (string), the application window that was active at the time of recording
    AMD = Active Monitor Detection (string), the number of monitors currently active
    PL = Process List (string), the list of processes currently running
    OW = Windows that are opened
    PuK = Public Key of the Invigilator's portal
    (example)
        "AWD" : "Telegram",
        "AMD" : "3",
        "PL" : "[Telegram.exe, google, svchost, ...]"
        "PuK" : XXXXXXXXXXXX
    Return: encrypted and encoded data in JSON
    """
    if request.method == 'POST':
        data = request.get_json()
        category = ''
        decoded = ''
        process_list = [] # we want the list to reset to empty everytime, if not the result will stack
        """
        Have to check for key to know the different proctoring feature's data that had been sent
        Since we will be sending different proctoring results at different timings
        Data is sent in base64 encoding
        """
        if data:
            # check if incoming data is the Public Key
            if "PuK" in data:
                # process the public key
                store_public_key(data["PuK"])
                PuK = True
                return jsonify(constructMacResponse())

            # check if there is a Public Key stored before processing the data
            elif PuK:
                if 'AWD' in data:
                    category = 'AWD'
                    # decoding data
                    # decode the data from base64 and convert to readable text
                    decoded = decodebase64(data[category]) 
                    
                    # processing data
                    processing(decoded, category)

                    return jsonify(constructDataResponse(decoded, category, gen_key()))
                
                if 'AMD' in data:
                    category = 'AMD'
                    # decoding data
                    # decode the data from base64 and convert to readable text
                    decoded = decodebase64(data[category])

                    # processing data
                    processing(decoded, category)

                    return jsonify(constructDataResponse(decoded, category, gen_key()))
                
                if 'PL' in data:
                    category = 'PL'
                    # decoding each item in the list of data
                    for item in data[category]:
                        # decode the data from base64 and convert to readable text
                        decoded = decodebase64(item)
                        process_list.append(decoded)

                    # processing data
                    processing(process_list, category)

                    return jsonify(constructDataResponse(process_list, category, gen_key()))

                if 'OW' in data:
                    category = 'OW'
                    # decoding each item in the list of data
                    for item in data[category]:
                        # decode the data from base64 and convert to readable text
                        decoded = decodebase64(item)
                        process_list.append(decoded)
                    
                    # processing data
                    processing(process_list, category)

                    return jsonify(constructDataResponse(process_list, category, gen_key()))
            else:
                # return 404, not found if there is no public key and data is received
                return('Public key not found', 404)

    # if any other methods were used but not allowed, return 200, success
    else:
        return ('', 200)
    
if __name__ == "__main__":
    app.run(host='0.0.0.0',debug=True)

