from uuid import uuid4
from random import randint
from basic_http import BasicHttp

def random_pk(length=8):
    s = uuid4().hex

    f = randint(0, (len(s) - length))
    t = f + length

    return s[f:t]

def get_url_info(url):
    req = BasicHttp(url)
    res = req.HEAD()

    data = {
        'file_name': url.split('/')[-1],
        'content_type': res['header']['Content-Type'],
        'file_size': res['header']['Content-Length']
    }

    return data


