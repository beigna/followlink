from random import randint
from PIL import Image, ImageFont, ImageDraw, ImageFilter

class Captcha(object):
    __slots__ = (
        '_text',
        '_font',
        '_size',
        '_image',
    )

    def __init__(self, *args, **kwargs):
        self._text = kwargs.get('text', False)
        self._size = kwargs.get('size', 50)
        self._font = 'captcha/font%d.ttf' % (randint(0,3))

    def _rectangle(self, max_x, max_y):
        width = randint(1, int(max_x * 0.15)) # 15% of X
        height = randint(1, int(max_y * 0.15)) # 15% of Y

        fp = ( # first point
            randint(0, (max_x - width)), # X
            randint(0, (max_y - height)) # Y
        )

        lp = ( # last point
            (fp[0] + width), # X
            (fp[1] + height) # Y
        )

        return (fp[0], fp[1], lp[0], lp[1])

    @staticmethod
    def random_text():
        art = ('un', 'el', 'la', 'una')
        sus = ('nene', 'vieja', 'nena',
            'viejo', 'joven', 'adulto',
            'perro', 'gato')
        ver = ('salta', 'corre', 'grita',
            'escala', 'mira', 'escucha',
            'habla', 'cae')

        return '%s %s %s' % (
            art[randint(0, 3)],
            sus[randint(0, 7)],
            ver[randint(0,7)]
        )

    def get_text(self):
        if not self._text:
            self._text = self.random_text()
        return self._text
    def set_text(self, value):
        self._text = value
    text = property(get_text, set_text)

    def generate(self):
        font_color = randint(0, 0xffff00)
        background_color = (font_color ^ 0xffffff)

        font = ImageFont.truetype(self._font, self._size)
        text_frame = font.getsize(self.text)

        image_width = text_frame[0] + 5
        image_height = text_frame[1] + 5

        image_frame = Image.new(
            'RGB', (image_width, image_height), background_color
        )

        draw = ImageDraw.Draw(image_frame)

        for i in range(20):
            draw.rectangle(
                self._rectangle(image_width, image_height),
                fill=randint(0, 0xffff00)
            )

        draw.text((3, 3), self.text, fill=font_color, font=font)
        self._image = image_frame.filter(ImageFilter.EDGE_ENHANCE_MORE)

    def save(self, file_path):
        self._image.save(file_path, format='JPEG')

