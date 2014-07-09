#!/usr/bin/python
#coding: utf-8
import alfred

alltypes = ['h', 'c', 'cpp', 'java', 'php', 'rb', 'md', 'txt']

def showFileList(query):
    result = []
    filetypes = []
    count = 1
    filetype = ''
    pos = query.find('.')
    if pos != -1:
        filetype = query[pos+1:]
        query = query[:query.index('.')]
        filetypes = [tp for tp in alltypes if tp.startswith(filetype)]
        if filetype not in filetypes and filetype!='':
            filetypes.insert(0, filetype)
            
    elif query!='':
        result.append(alfred.Item({'uid': count, 'arg': query}, 'Make new file..', 'file name "' + query + '"', 'default.png'))
        count += 1
        if 'Makefile'.startswith(query):
            result.append(alfred.Item({'uid': count, 'arg': 'Makefile'}, 'Make new file..', 'file name "Makefile"', 'make.png'))
            count += 1
        if 'makefile'.startswith(query):
            result.append(alfred.Item({'uid': count, 'arg': 'makefile'}, 'Make new file..', 'file name "makefile"', 'make.png'))
            count += 1

        filetypes = alltypes
        
    for ch in filetypes:
        filename = query + '.' + ch
        result.append(alfred.Item(
            {'uid': count, 'arg': filename},
            'Make new file..',
            'file name "' + filename + '"',
            (ch, {'type': 'filetype'})
        ))
        count += 1
        
    xml = alfred.xml(result)
    alfred.write(xml)