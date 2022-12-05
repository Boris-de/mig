# mig

Mig is a photo album / image gallery management system.

It is written in PHP and requires nothing but PHP to operate.

See https://mig.wcht.de/ for details and contact.

Documentation and changelog for older releases is also located on the website
https://mig.wcht.de/docs/ or just look in the `docs` folder.

See https://github.com/Boris-de/mig-contrib for helpful utilities.

![CI](https://github.com/Boris-de/mig/workflows/CI/badge.svg)


## Development

To run a simple development instance you can use `make dev-server` to start
a simple local webserver on port 8080 with a random example album,
alternatively you can also run `make dev-server DEV_SERVER_PORT=8000` to
specify a different port.

To run the tests you can use `make unittests` which requires phpunit to be
installed. To generate code coverage you can run `make coverage` which needs
xdebug.
With `make container-unittests` you can run the unittests in a container (run
make with `DOCKER=podman` as a parameter to use podman) and
`make container-unittests-all` runs the unittests in different versions of PHP.

If you have psalm installed you can run the static analysis with `make psalm`.

To build the index.php and run it with different PHP versions you can use
`make container CONTAINER_PHP_VERSION=7.4` which opens a random local port on a
randomly generated album.

## Documentation

To be build the documentation you need to have docker or pandoc installed.
Building with dockerized pandoc can be easily done with `make docs`
while building with system pandoc can be done with `make -C docs pandoc_cmd=pandoc`

## Releasing

To build a release a few variables need to be set to specify the version of the
release, the gpg information for signing and the local target directory for the
documentation. You also need the prerequisites mentioned above, especially docker
to run the unittests on all supported PHP versions.
```
make clean release VERSION=... MIG_GPG_KEY="..." MIG_GPG_EMAIL="..." MIG_SITE_DIR="..."
```


## Git tags

Most of the tags are converted from the original CVS, but somewhere missing
and added later (v1.2.7, v1.2.7p1, v1.2.8, v1.2.9r, v1.3.0) based the commit
messages and a comparison of the content of the release tarballs. For 1.2.9 a
slight difference between the commit and the tar exists, hence the "r"-suffix
