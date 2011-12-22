
from django import forms
from django.utils.safestring import mark_safe

class CaptchaWidget(forms.TextInput):
    def render(self, name, value, attrs=None):
        output = u'<img src="/captcha/%s.jpg" />' % name
        return mark_safe(output)

