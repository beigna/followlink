from django.db import models

from links.functions import random_pk, get_url_info

# Create your models here.

class Link(models.Model):

    id = models.CharField(max_length=8, primary_key=True, editable=False)

    url = models.URLField(verify_exists=True, max_length=512)
    file_name = models.CharField(max_length=128, blank=True)
    content_type = models.CharField(max_length=32, blank=True)
    file_size = models.PositiveIntegerField(blank=True)

    hits = models.PositiveIntegerField(default=0, editable=False)
    last_hit = models.DateTimeField(default='1984-09-28 06:50:00',
        editable=False)

    updated_at = models.DateTimeField(auto_now=True, editable=False)
    created_at = models.DateTimeField(auto_now_add=True, editable=False)

    def __unicode__(self):
        return u'%s (%s)' % (self.file_name, self.content_type)

    def save(self, *args, **kwargs):
        if not self.pk:
            self.id = random_pk()

            if True:
                url_info = get_url_info(self.url)
                self.file_name = url_info['file_name']
                self.content_type = url_info['content_type']
                self.file_size = url_info['file_size']
            #except:
            #    pass

        return super(Link, self).save(*args, **kwargs)
