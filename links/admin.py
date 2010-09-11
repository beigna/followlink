from django.contrib import admin

from links.models import Link
#

class LinkAdmin(admin.ModelAdmin):
    list_display = ('id', 'file_name', 'content_type', 'file_size', 'url')

admin.site.register(Link, LinkAdmin)
