Etherpad-Lite for ~friendica
============================

This addon embeds an [etherpad-lite][1] app into your ~[friendica][2] installation.

The app is accessable via the "apps" menu of ~friendica but allows only your
members to access the etherpad-lite installation. It also passes the users
nickname forward to etherpad-lite as the author.

![a screen shot of the addon in action](https://bitbucket.org/tobiasd/friendica-etherpad-lite/raw/4c5b42be4a3c489e33f47462b54f2997c16528f9/screenshot.jpg)

[1]:https://github.com/ether/etherpad-lite
[2]:http://friendica.com

Configuration
-------------

Enter the full URL of your etherpad-lite installation and you are done. E.g. if
your etherpad-lite is installaled at http://pad.example.com enter exactly this
into the settings.

If you only want to allow the access to a single pad you can do so too.
Assuming the pad was created with the name "funny-bugs" enter the following URL
into the config: http://pad.example.com/p/funny-bugs

Bugs
----

Please use the tracker at the [Bitbucket repository of this addon][3].

[3]:https://bitbucket.org/tobiasd/friendica-etherpad-lite

License
-------

Copyright (c) 2012 Tobias Diekershoff
All rights reserved.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
