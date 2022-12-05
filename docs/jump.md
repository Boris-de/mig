Mig - Jump Tags
===============

## What Are Jump Tags and How Do They Work?

What is a jump tag?  Well, many people (including the author) didn't like
the really long URLs that Mig uses because it made it clumsy to paste them
into an email or a chat window.  However, changing them now would be
difficult to do and certainly not backward-compatible.

Jump tags were invented to address this problem.  Take this URL for
example:

https://example.com/gallery/index.php?pageType=folder&currDir=./Our%20House/Before%20We%20Redecorated

At 105 characters long, and having tons of URL-encoded `%xx` entities
in it, it's an ugly URL to send someone.  But the pain can be alleviated.
The jump map defines shortcuts for long URLs.

Add a line to `config.php` that adds to the jump map.

    $jumpMap['me'] = 'currdir=./Pictures_of_me';

Now "me" is defined as a shortcut for a folder at the root of
the album called "Pictures of me".  The long URL above
would look like this:

    $jumpMap['house'] = 'currDir=./Our%20House/Before%20We%20Redecorated';

All that is needed is the value of `currDir` (which can be copied from the
address bar in most web browsers).  Now this URL will go to the same place:

    https://example.com/gallery/?jump=house

Much shorter and nicer.  If an album has four or five levels deep (such as
some of the albums I maintain), the URLs can be very long.

There are two ways to do this, too.  The first is shown above.  Another way
is a URL like this:

    https://example.com/gallery/index.php/house

Which avoids clumsy stuff like the ? in the URL.  It's a matter of
preference which one to use.  Note that with the second method it can't
be shortened like this:

    https://example.com/gallery/house

Because the web server will look for that directory, not find it, and
return an error.  So that `index.php` or `index.php3` has to be in
there.

This works with Apache web servers.  I don't know if it works with
anything else.  If you're running something other than Apache, let me
know how you fare with this feature.

## Tips for Apache Users

Also see the `apache` document which has some nifty
`mod_rewrite` ideas to utilize shorter, simpler URLs.
