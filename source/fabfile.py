#!/usr/bin/env python
# -*- coding: utf-8 -*-

from fabric.api import *
import os
from datetime import datetime

FILES = []
EXT = ('php', 'ttf')
NO_UPLOAD = ()

APP_NAME = 'followlink'
APP_PACKAGE = '%s-%s.tbz2' % (APP_NAME,
    datetime.utcnow().strftime('%Y-%m-%d_%H-%M-%S'))
APP_PATH = '/PATH_TO_YOU/VHOST/%s' % (APP_NAME)

all_files = os.walk('./')
for path, dirnames, filenames in all_files:
    for filename in filenames:
        if filename in NO_UPLOAD:
            continue
        else:
            if filename.split('.')[-1] in EXT:
                if path[2:]:
                    FILES.append('%s/%s' % (path[2:], filename))
                else:
                    FILES.append('%s' % (filename))

def staging():
    env.hosts = ['localhost']
    env.user = 'CHANGE_ME'
    env.password = 'CHANGE_ME'

def production():
    env.hosts = ['CHANGE_ME']
    env.user = 'CHANGE_ME'
    env.password = 'CHANGE_ME'

def update():
    'Update the software version'
    local('/bin/tar cfj %s %s' % (APP_PACKAGE, ' '.join(FILES)))
    put(APP_PACKAGE, APP_PACKAGE)
    run('/bin/tar xfj %s -C %s' % (APP_PACKAGE, APP_PATH))
    local('/bin/rm %s' % (APP_PACKAGE))

def install():
    sudo('mkdir -p %s' % (APP_PATH))

    update()
