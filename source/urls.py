from django.conf.urls.defaults import *

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Example:
    # (r'^followlink/', include('followlink.foo.urls')),

    # Uncomment the admin/doc line below and add 'django.contrib.admindocs'
    # to INSTALLED_APPS to enable admin documentation:
    # (r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    (r'^admin/', include(admin.site.urls)),

    (r'^captcha/', include('captcha.urls')),

    url(r'^(?P<id>\w{8})$', 'links.views.follow', name='link_follow'),
    (r'^info/(?P<id>\w{8})$', 'links.views.info'),
    (r'^$', 'links.views.home'),
)

