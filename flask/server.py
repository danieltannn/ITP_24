from flask import Flask, jsonify, request
import base64

app = Flask(__name__)

blacklist = ['telegram', 'whatsapp', 'spotify']
TRIGGER = 0
updateInterval = False

@app.route("/", methods = ['POST'])
def index():
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
    Return: encrypted and encoded dictionary in JSON
    """
    if request.method == 'POST':
        data = request.get_json()
        """
        Have to check for key to know the different proctoring feature's data that had been sent
        Since we will be sending different proctoring results at different timings
        Data is sent in base64 encoding
        """
        if data:
            if 'AWD' in data:
                decoded = base64.b64decode(data['AWD']).decode('UTF-16LE') # decoding data
                '''
                Things to do: after decoding we need to process -> encrypt "decoded". 
                '''
                return '{data}'.format(data=decoded) # Things to do: Send encoded + encrypted data back instead of decoded
            
            if 'AMD' in data:
                decoded = base64.b64decode(data['AMD']).decode('UTF-16LE') # decoding data
                '''
                Things to do: after decoding we need to process -> encrypt "decoded" -> encode. 
                '''
                return '{data}'.format(data=decoded) # Things to do: Send encoded + encrypted data back instead of decoded
            
            if 'PS' in data: 
                process_list = [] # we want the list to reset to empty everytime, if not the result will stack
                for items in data['PS']: # decoding a list of encoded data
                    decoded = base64.b64decode(items).decode('UTF-16LE')
                    '''
                    Things to do: after decoding we need to process -> encrypt "decoded" -> encode. 
                    '''
                    process_list.append(decoded) # Things to do: Append encryped data
                return '{data}'.format(data=process_list) # Things to do: Send encoded + encrypted data list back instead of decoded
            
            if 'OW' in data: 
                process_list = [] # we want the list to reset to empty everytime, if not the result will stack
                for items in data['OW']: # decoding a list of encoded data
                    decoded = base64.b64decode(items).decode('UTF-16LE')
                    '''
                    Things to do: after decoding we need to process -> encrypt "decoded" -> encode. 
                    '''
                    process_list.append(decoded) # Things to do: Append encryped data
                return '{data}'.format(data=process_list) # Things to do: Send encoded + encrypted data list back instead of decoded

def processing(data):
    global TRIGGER, updateInterval
    try:
        if data["AWD"] in blacklist:
            TRIGGER += 1
            print(data["AWD"])
            updateInterval = True
    except Exception as e:
        print(e)
    
    try:
        if int(data["AMD"]) > 4:
            TRIGGER += 1
            print(data["AMD"])
            updateInterval = True
    except Exception as e:
        print(e)

    try:
        if any(element in data["PL"] for element in blacklist):
            TRIGGER += 1
            print(data["PL"])
            updateInterval = True
    except Exception as e:
        print(e)
            
if __name__ == "__main__":
    app.run(host='0.0.0.0',debug=True)

