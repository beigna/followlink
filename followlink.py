import web
from simplejson import dumps as simplejson_dumps

urls = (
  '/', 'index',
  '/new', 'new',
  '/(\d+)', 'follow',
  '/stats/(\d+)', 'stats'
)

app = web.application(urls, globals())
render = web.template.render('templates/')

class index:
    def GET(self):
        return render.home()

class new:
    def GET(self):
        return render.base()

class follow:
    def GET(self, id):
        return id

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
