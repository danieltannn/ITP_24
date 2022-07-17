from flask import Flask, jsonify, request
import base64

app = Flask(__name__)

@app.route("/", methods = ['POST'])
def index():
    """POST - Receive data that was captured by the proctoring script

    Keyword arguments:
    (from the host)
    AWD = Active Windows Detection (string), the application window that was active at the time of recording
    AMD = Active Monitor Detection (string), the number of monitors currently active
    PL = Process List (string), the list of processes currently running
    (example)
    {
        "AWD" : "qwerfqwefqwefqwef qwef ==",
        "AMD" : "3",
        "PL" : "[Telegram.exe, google, svchost, ...]"
    }
    Return: success 200
    """
    if request.method == 'POST':
        data = request.get_json()

        '''
        Have to check for key to know the different proctoring feature's data that had been sent
        Since we will be sending different proctoring results at different timings
        Data is sent in base64 encoding
        '''
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
                Things to do: after decoding we need to process -> encrypt "decoded". 
                '''
                return '{data}'.format(data=decoded) # Things to do: Send encoded + encrypted data back instead of decoded
            
            if 'PS' in data: 
                process_list = [] # we want the list to reset to empty everytime, if not the result will stack
                for items in data['PS']: # decoding a list of encoded data
                    decoded = base64.b64decode(items).decode('UTF-16LE')
                    '''
                    Things to do: after decoding we need to process -> encrypt "decoded". 
                    '''
                    process_list.append(decoded) # Things to do: Append encryped data
                return '{data}'.format(data=process_list) # Things to do: Send encoded + encrypted data list back instead of decoded
            
            if 'OW' in data: 
                process_list = [] # we want the list to reset to empty everytime, if not the result will stack
                for items in data['OW']: # decoding a list of encoded data
                    decoded = base64.b64decode(items).decode('UTF-16LE')
                    '''
                    Things to do: after decoding we need to process -> encrypt "decoded". 
                    '''
                    process_list.append(decoded) # Things to do: Append encryped data
                return '{data}'.format(data=process_list) # Things to do: Send encoded + encrypted data list back instead of decoded
            
if __name__ == "__main__":
    app.run(host='0.0.0.0',debug=True)
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

