## Latest Tweets

This addon enables your users to embed a [jQuery widget][1] with a Twitter
search in two places. Both places can be configured individually in terms of
the color selection and the search item.

One place for the widget is the sidebar of the network stream, which is seen
for the user only. The other place is at the bottom of the profile page, which
is displayed for everyone.

### Installation

Assuming that you have downloaded the addon archive either directly from the
repository at bitbucket or from friendica-addons.com. Extract the content of
the archive and make sure it is named "lasttweets" containing three files and
one subdirectory "js" which contains one file. Transfer the "lasttweets"
directory to your server and place it in the addon directory of your friendica
installation, so that the file structure looks something like

    $friendica
      /addon
        /lasttweets
          /js
            jquery.twitter.search.js 
          lasttweets.php  
          README.md  
          style.css

You can also clone the mercurial repository from [BitBucket][3] into a
directory called "lasttweets" located in your addon subdirectory of your
friendica installation-

### Screenshots

![Twitterbox below the profile](https://bitbucket.org/tobiasd/friendica-lasttweets-widget/raw/943f7fb12cf79abee4f5735c55c33ab741e58552/images/friendica-profile-twitterbox.jpg)

![Twitterbox in the network sidebar](https://bitbucket.org/tobiasd/friendica-lasttweets-widget/raw/943f7fb12cf79abee4f5735c55c33ab741e58552/images/friendica-network-twitterbox.jpg)

### Configuration

Go to _Settings -> Plugin settings_ and scroll down to _Twitter Widget
Settings_.

There you have the configuration forms for both boxes where you can define the
search item, the inner and outer color and disable the boxes.

### License

The [jQuery Twitter search][1] module of Mike Alsup is dual licensed under the MIT
and the GPL license.

The [~friendica addon][2] is licensed under the MIT license.

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

[1]:https://github.com/malsup/twitter
[2]:http://friendica.com
[3]:https://bitbucket.org/tobiasd/friendica-lasttweets-widget
