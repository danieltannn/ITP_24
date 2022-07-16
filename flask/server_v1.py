from flask import Flask, jsonify, request
import threading

app = Flask(__name__)

blacklist = ['telegram', 'whatsapp', 'spotify']
TRIGGER = 0
updateInterval = False

@app.route("/", methods = ['GET', 'POST'])
def index():
    global TRIGGER, updateInterval

    """POST - Receive data that was captured by the proctoring script
    
    Keyword arguments:
    (from the host)
    AWD = Active Windows Detection (string), the application window that was active at the time of recording
    AMD = Active Monitor Detection (string), the number of monitors currently active
    PL = Process List (string), the list of processes currently running
    (example)
    {
        "AWD" : "Telegram.exe",
        "AMD" : "3",
        "PL" : "<WHATEVER PROCESSES ARE IN THE LIST"
    }
    Return: success 200
    """
    if request.method == 'POST':
        data = request.get_json()
        threading.Thread(target=processing(data), name="processing")
        return "<p>method = POST \n {data}</p>".format(data = data)


    """GET - for PC to poll for updates to send to the web server
    
    Keyword arguments:
    response = dictionary of items for the PC to unpack
        data = encrypted data to send to the web server
        trigger = number of times anomalies were detected (suspected cheating)
            - will only be sent if the updateInterval flag is true
    Return: response in json format
    """

    if request.method == 'GET':
        response = {}
        response["data"] = "some encrypted data here"
        if updateInterval:
            response["trigger"] = TRIGGER
            updateInterval = False
        return jsonify(response)

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
