Mig and the Apache Server
=========================

## Security Considerations

There are always security considerations when dealing with anonymous
external users accessing a server.  Mig mitigates these concerns where it
can (for instance, a `currDir` variable containing ".." causes
Mig to panic and immediately exit), but it can't do everything.

The main thing Mig does to help with the security problem is that it never,
ever, writes anything to the disk.  It only reads.  This minimizes the
potential damage a malicious attacker could do.  (That's why Mig doesn't
automatically generate thumbnails, by the way.)

An example: Mig's PHP code can't control whether a web user fetches
`mig.cf` or `exif.inf` files by simply using direct URLs (since
using direct URLs bypasses Mig and PHP altogether).  Users of Apache can
use the following in their configuration to protect their files from being
read:

    <Directory /path/to/your/mig/gallery>
        # Protect against anyone trying to view mig.cf or exif.inf files
        <Files ~ "^(mig\.cf|exif\.inf)$">
            order allow,deny
            deny from all
        </Files>
    </Directory>

With this rule in place, someone can't for example go to a URL like this
one:

    https://example.com/gallery/albums/Miscellaneous/mig.cf

It would be met with an access denial from the server.

## Useful Rewrite Ideas

Mig URLs can be made shorter and easier to remember by using Apache's
rewrite rules.  This requires `mod_rewrite` to be available.
Here are some examples from my site:

First, define simple shortcut names using the jump map (see the
`jump` document).

Then, add a rule or two like this to `httpd.conf`:

    RewriteRule ^/go/([^/]+)        /gallery/index.php?jump=$1    [R]
    RewriteRule ^/photo/([^/]+)     /gallery/index.php?jump=$1    [R]

Now URLs like these will work:

    https://example.com/photo/kate
    https://example.com/go/kate

Further shortening can be done if desired:

    RewriteRule ^/kate           /gallery/index.php?jump=kate     [R]
    RewriteRule ^/house          /gallery/index.php?jump=house    [R]
    RewriteRule ^/europe         /gallery/index.php?jump=europe   [R]

For example, now https://example.com/kate/ will work.  Of course, doing
things this way would require more maintenance than the more general
jump-based rules shown above.

Naturally, `RewriteEngine on` is required in the config prior to using any
rewrite rules.  See Apache's documentation for more information about the
rewriting engine.
