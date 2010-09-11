
from django import forms
from django.utils.safestring import mark_safe

class CaptchaWidget(forms.TextInput):
    def render(self, name, value, attrs=None):
        output = u'<img src="/captcha/add.jpg" />'
        return mark_safe(output)

