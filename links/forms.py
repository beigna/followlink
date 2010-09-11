
from django import forms

from links.models import Link
from captcha.forms import CaptchaWidget

class LinkAddForm(forms.ModelForm):
    def __init__(self, captcha_text, *args, **kwargs):
        self._captcha_add = captcha_text
        super(LinkAddForm, self).__init__(*args, **kwargs)

    class Meta:
        model = Link
        fields = ('captcha_image', 'captcha_text', 'url')
        exclude = ('file_name', 'content_type', 'file_size')

    captcha_image = forms.CharField(widget=CaptchaWidget, required=False)
    captcha_text = forms.CharField()

    def clean(self):
        if self.cleaned_data.get('captcha_text') != self._captcha_add:
            raise forms.ValidationError('The captcha text do not match with image text.')

        return self.cleaned_data

class LinkFollowForm(forms.Form):
    captcha_image = forms.CharField(widget=CaptchaWidget)
    captcha_text = forms.CharField()

