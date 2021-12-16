from flask import Flask, escape, request

app = Flask(__name__)


@app.route('/', methods=['POST', 'GET'])
def hello():
    name = request.args.get("name", "World")
    return f'Hello, <markee>matu!</markee>'


app.run(debug=True)
