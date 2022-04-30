# artist-follow
Sample app for following multiple artists on Spotify.

### Dependencies

PHP

### Installation

Copy the contents of the src folder into a `follow` folder under web root (`/Library/WebServer/Documents` for Apache on Mac OS). Copy the follow.template.ini file under web root and fill it out with your app's details from Spotify Developer Dashboard.

Navigate to `http://localhost/follow` and select the `Login to Spotify` button, followed by `Follow All Artists`. 

### Resources

- [Spotify Auth Example](https://github.com/spotify/web-api-auth-examples)
- [Implicit Grant Flow](https://developer.spotify.com/documentation/general/guides/authorization/implicit-grant/)
- [Spotify Follow API](https://developer.spotify.com/console/put-following/)


