
from django.http import HttpResponse

import cStringIO
import httplib

from captcha import Captcha

# Create your views here.

def captcha_show(request, name):
    key = 'captcha_%s' % name

    if not request.session.get(key, False):
        c = Captcha()
        request.session[key] = c.text
    else:
        c = Captcha(text=request.session.get(key))

    fp_captcha = cStringIO.StringIO()
    c.generate()
    c.save(fp_captcha)
    fp_captcha.seek(0)

    res = HttpResponse(status=httplib.OK)
    res['Content-Type'] = 'image/jpg'
    res.write(fp_captcha.read())

    return res

