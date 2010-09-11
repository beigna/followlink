
import web
import model

from simplejson import dumps as simplejson_dumps

urls = (
  '/', 'index',
  '/new', 'new',
  '/(\w{8})', 'follow',
  '/stats/(\d+)', 'stats'
)

app = web.application(urls, globals())
render = web.template.render('templates/')
#session = web.session.Session(app, web.session.DBStore(model.db, 'session'))

class index:
    def GET(self):
        return render.home()

class new:
    def GET(self):
        return render.base()

class follow:
    def GET(self, id):
        data = model.get_link(id)
        return render.follow(data)

    def POST(self, id):
        i = web.input()
        if i.captcha_follow == 'hola':
            data = model.get_link(id)
            raise web.Found(data.url, absolute=True)
        else:
            raise web.SeeOther('/%s' % (id))

class stats:
    def GET(self, id):
        data = simplejson_dumps({
            'last-download': '2010-09-02 00:33:14',
            'count': 54,
            'id': id
        })

        web.header('Content-Type', 'application/json')
        return data


if __name__ == "__main__":
    app.run()
