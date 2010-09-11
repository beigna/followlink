from django.conf.urls.defaults import patterns, url

urlpatterns = patterns('captcha.views',
    url(r'^(?P<name>\w+).jpg$', 'captcha_show', name='captcha_show'),
)
