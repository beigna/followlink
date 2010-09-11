
from django.shortcuts import get_object_or_404, render_to_response, redirect
from django.http import HttpResponse
from django.core.urlresolvers import reverse
from django.template import RequestContext

import httplib
from simplejson import dumps

from captcha.captcha import Captcha
from links.models import Link
from links.forms import LinkAddForm, LinkFollowForm

# Create your views here.

def follow(request, id):
    if not request.session.get('captcha_add', False):
        request.session['captcha_add'] = Captcha.random_text()

    link = get_object_or_404(Link, pk=id)

    if request.method == 'GET':
        form = LinkFollowForm('')

    elif request.method == 'POST':

    return render_to_response('home.html',
        {
            'link_form': form,
            'link': link
        },
        context_instance=RequestContext(request)
    )

def info(request, id):
    link = get_object_or_404(Link, pk=id)

    data = dumps({
        'file_name': link.file_name,
        'content_type': link.content_type,
        'file_size': link.file_size,
        'hits': link.hits,
        'last_hit': link.last_hit.strftime('%Y-%m-%d %H:%M:%S')
    })

    res = HttpResponse(status=httplib.OK)
    res['Content-Type'] = 'application/json'
    res.write(data)

    return res

def home(request):
    if not request.session.get('captcha_add', False):
        request.session['captcha_add'] = Captcha.random_text()

    if request.method == 'GET':
        form = LinkAddForm('')

    elif request.method == 'POST':
        link = Link()
        form = LinkAddForm(request.session.get('captcha_add'),
            instance=link, data=request.POST)

        request.session['captcha_add'] = ''
        if form.is_valid():
            link = form.save()
            return redirect('/')



    return render_to_response('home.html',
        {
            'link_form': form,
        },
        context_instance=RequestContext(request)
    )

