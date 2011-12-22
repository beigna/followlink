import web
from datetime import datetime

from basic_types import String, Integer, DateTime

class FollowLink(object):
    __slots__ = (
        '_content_type',
        '_created_at',
        '_file_name',
        '_file_size',
        '_hits',
        '_id',
        '_last_hit',
        '_updated_at',
        '_url',
        '_user_id'
    )

    def __init__(self, *args, **kwargs):
        self.content_type = kwargs.get('content_type')
        self.created_at = kwargs.get('created_at')
        self.file_name = kwargs.get('file_name')
        self.file_size = kwargs.get('file_size')
        self.hits = kwargs.get('hits')
        self.id = kwargs.get('id')
        self.last_hit = kwargs.get('last_hit')
        self.updated_at = kwargs.get('updated_at')
        self.url = kwargs.get('url')
        self.user_id = kwargs.get('user_id', 0)

    def __unicode__(self):
        return u'%s (%s)' % (self.file_name, self.content_type)

    def __str__(self):
        return self.__unicode__().encode('utf-8')
    #
    def get_content_type(self):
        return self._content_type.value
    def set_content_type(self, value):
        self._content_type = String(value)
    content_type = property(get_content_type, set_content_type)
    #
    def get_created_at(self):
        return self._created_at.value
    def set_created_at(self, value):
        self._created_at = DateTime(value)
    created_at = property(get_created_at, set_created_at)
    #
    def get_file_name(self):
        return self._file_name.value
    def set_file_name(self, value):
        self._file_name = String(value)
    file_name = property(get_file_name, set_file_name)
    #
    def get_file_size(self):
        return self._file_size.value
    def set_file_size(self, value):
        self._file_size = Integer(value)
    file_size = property(get_file_size, set_file_size)
    #
    def get_hits(self):
        return self._hits.value
    def set_hits(self, value):
        self._hits = Integer(value)
    hits = property(get_hits, set_hits)
    #
    def get_id(self):
        return self._id.value
    def set_id(self, value):
        self._id = String(value)
    id = property(get_id, set_id)
    #
    def get_last_hit(self):
        return self._last_hit.value
    def set_last_hit(self, value):
        self._last_hit = DateTime(value)
    last_hit = property(get_last_hit, set_last_hit)
    #
    def get_updated_at(self):
        return self._updated_at.value
    def set_updated_at(self, value):
        self._updated_at = DateTime(value)
    updated_at = property(get_updated_at, set_updated_at)
    #
    def get_url(self):
        return self._url.value
    def set_url(self, value):
        self._url = String(value)
    url = property(get_url, set_url)
    #
    def get_user_id(self):
        return self._user_id.value
    def set_user_id(self, value):
        self._user_id = Integer(value)
    user_id = property(get_user_id, set_user_id)
    #

db = web.database(dbn='mysql', db='followlink', user='root')

def get_link(id):
    data = db.select('link', where='id=$id', vars=locals())[0]
    followlink = FollowLink(**dict(data))
    return followlink
