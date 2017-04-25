# Youtube And Lyrics (YAL)

This repo features a combination of Youtube API and Musixmatch API. You search a song (artist and song name), choose the one you like from the list and get the video and lyrics on the same page. This is a non commercial project, so the lyrics API display just 30% of them, because I don't pay for using their API.

# Technologies

* [Youtube API](https://developers.google.com/youtube/v3/docs/search/list)
* [Musixmatch API](https://developer.musixmatch.com/)
* [Symfony2](https://symfony.com/doc/2.8/index.html)
* [Bootstrap](http://getbootstrap.com/)

# Installation

To install this project you need to install Symfony2 first.

```bash
sudo mkdir -p /usr/local/bin
sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
sudo chmod a+x /usr/local/bin/symfony
```

After that navigate to whatever directory you want to have this project in and run this command:

```bash
symfony new my_project_name
```

Replace files in the folder with files from this repo.

To start the server, run this command:

```bash
php app/console server:run
```
