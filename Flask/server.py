from flask import Flask, jsonify, request
from functions import *

app = Flask(__name__)

HTTP_METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH', 'BREW']

@app.route("/", methods = HTTP_METHODS)
def index():
    global TRIGGER, UPDATEINTERVAL

    """POST - Receive data that was captured by the proctoring script

    Keyword arguments:
    (from the host)
    AWD = Active Windows Detection (string), the application window that was active at the time of recording
    AMD = Active Monitor Detection (string), the number of monitors currently active
    PL = Process List (string), the list of processes currently running
    OW = Windows that are opened
    (example)
    {
        "AWD" : "Telegram",
        "AMD" : "3",
        "PL" : "[Telegram.exe, google, svchost, ...]"
    }
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
            if 'AWD' in data:
                # decoding data
                category = 'AWD'
                decoded = decodebase64(data[category]) 
                
                # processing data
                processing(decoded, category)

                return jsonify(constructResponse(decoded, category, gen_key()))
            
            if 'AMD' in data:
                category = 'AMD'
                # decoding data
                decoded = decodebase64(data[category])

                # processing data
                processing(decoded, category)

                return jsonify(constructResponse(decoded, category, gen_key()))
            
            if 'PL' in data:
                category = 'PL'
                # decoding each item in the list of data
                for item in data[category]:
                    decoded = decodebase64(item)
                    process_list.append(decoded)

                # processing data
                processing(process_list, category)

                return jsonify(constructResponse(process_list, category, gen_key()))

            if 'OW' in data:
                category = 'OW'
                # decoding each item in the list of data
                for item in data[category]:
                    decoded = decodebase64(item)
                    process_list.append(decoded)
                
                # processing data
                processing(process_list, category)

                return jsonify(constructResponse(process_list, category, gen_key()))

    # if any other methods were used but not allowed, return 200, success
    else:
        return ('', 200)
    
if __name__ == "__main__":
    app.run(host='0.0.0.0',debug=True)

