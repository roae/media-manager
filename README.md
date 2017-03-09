>#### Clone [talvbansal/media-manager](https://github.com/talvbansal/media-manager)
>Laravel 5.1 support added


# Media Manager

Media manager is a basic file uploader and manager component for **Laravel** written in **Vue.js 2.0**
 

## # Introduction
Media Manager provides a simple way for users to upload and manage content to be used throughout your project.

## # Requirements

- [PHP](https://php.net) >= 5.6
- [Composer](https://getcomposer.org)
- An existing [Laravel 5.1](https://laravel.com/docs/5.1/installation) project

## # Installation
To get started, install Media Manager via the Composer package manager:  

Add to `composer.json` file: 
```json
//composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/roae/media-manager.git"
        }
    ]
}
```

Next, add to require object the `roae/media-manager@2.0.*` dependency

```json
    "roae/media-manager": "2.0.*",
```

Next, install it using the command:
```bash
composer update
```
After composer downloads and installs the package, registers the Media Manager Services Provider in the `providers` configuration and your `app/config.php` configuration file:
```php
\Roae\MediaManager\Providers\MediaManagerServiceProvider::class,
```

The Media Manager service provider **does not** automatically register routes for the Media Manager to work. This is so that you can add custom middleware around those routes. You can register all of the routes required for the Media Manager by adding the following to your `routes/web.php` file: 
```php
\Roae\MediaManager\Routes\MediaRoutes::get();
```

After registering the Media Manager service provider, you should publish the Media Manager assets using the `vendor:publish` Artisan command: 
```bash
# PUBLISH ASSETS
php artisan vendor:publish --tag=media-manager-assets --force
```
Media Manager assets are **not** published to the `public` folder as would be normally expected, instead they will be published to `/resources/assets/talvbansal`.
Since the Media Manger is written in `vue.js 2.0` you'll need to use webpack or another bundler to get the code ready for the browser. You can then bundle these with your existing scripts in your projects `gulpfile.js`.
```javascript
//gulpfile.js
var elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir(function(mix) {

    // Add additional styles...
    mix.sass([
        '../roae/media-manager/css/media-manager.css',
        'app.scss'
    ]);
   
    // Combine the various JS into one.
      mix.scripts([
        'app.js',
        '../../resources/assets/roae/media-manager/js/media-manager.js',
      ], null, 'public/js');

    // Add dependencies and components...
    mix.webpack(['../../../public/js/all.js']);

    // Copy SVG images into the public directory...
      mix.copy( 'resources/assets/roae/media-manager/fonts', 'public/fonts' );
});
```
After publish the assets files, you need to publish routes for the package using the artisan command `vendor:publish`:
```bash
# PUBLISH ROUTES
php artisan vendor:publish --tag=media-manager-routes --force
```

This command will create the `app/Http/media-manager-routes.php` file, then you must be include this file on your `routes.php` file or create your own Media Manager routes.

```php
require app_path('Http/media-manager-routes.php');
```
Next, add the `public` disk on `config/filesystems.php` file
```php
'disk' => [
    //....
    'public' => [
        'driver' => 'local',
        'root'   => storage_path('app/public'),
    ],
]
```

The media manager uses the `public` disk to store its uploads. The storage path for the `public` disk by default is `storage/app/public`.
To make these files accessible from the web, add the following route to your `app/Http/routes.php` file:
```php
Route::get('storage/{path}',function($path){
    $disk = Storage::disk('public');
    if($disk->has($path)){
        $file = $disk->get($path);
        $mime_type = $disk->mimeType($path);
        return Response::make($file, 200, ['Content-Type' => $mime_type]);
    }
})->where('path', '.+');
```
This could work using `.htaccess` file and `mod_rewrite` activated on apache.

## # Getting starter

The Media Manager is written in `vue.js 2.0` and comes bundled with all the dependencies required to get going very quickly.
After you've added the dependencies to your layout if your project doesn't already use `vue.js 2.0` you'll need to create a **Vue instance** on the page that you want to use the Media Manager on:
```javascript
<script>
    new Vue({
        el : '#app'
    });
</script>
```
This tells Vue to use an element with the id of `app` on your page as its container - a specific area in which `vue.js` will interact. Vue will not interact with anything outside of this element.

You will also need to add the following to your layout if it doesn't already exist.
It provides the `csrfToken` used for the `vue-resource` http requests that the Media Manager will make.
```javascript
<script>
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
    ]) !!}
</script>
```

## # Media Manager Components

The Media Manager package will register 2 new usable `vue.js` components:
- `<media-manager>`
- `<media-modal>`

The `<media-manager>` component is the core component that provides all of the Media Manager functionality and `<media-modal>` is a component used to build the internal modal windows of the Media Manager.
The `<media-modal>` component can also be used to open the Media Manager itself inside a modal window.

#### # Stand Alone Media Manager

If you just need an instance of the Media Manager getting started is easy.
Just create a `<media-manager>` tag within the scope of your Vue instance:
```html
<body>
    <div id="app">
        <media-manager></media-manager>
    </div>
</body>
```
This will create a Media Manager that will allow you to do all of the following:
- Navigate directories
- Upload new files
- Create new folders
- Rename items
- Move items
- Delete items
    
#### Modal Window Media Manager

Setting up a Media Manager within a modal window requires a bit more markup and configuration.

You'll need to do the following:

1. Create a `<media-manager>` component nested within a `<media-modal>`  component.
2. Add the `:is-modal="true"` property to the Media Manager component : `<media-manager :is-modal="true">`
3. Create a way to open and close the modal window.
    - Within the data object of your root Vue instance create a boolean property to hold the visible state of the modal window with a default value of `false`, `showMediaManager = false`.
    - Add a `v-if` directive to the `<media-modal>` component and use the newly created `showMediaManager` property to toggle the modal window's visibility, `<media-modal v-if="showMediaManager"></media-modal>`.
    - Create a button to open the modal window and get it change the property bound to the modal window's `show` property to `true`
    - Add listeners for the `@close` event to the `<media-modal>` and `<media-manager>` components so that they can close the modal window

Here is an example of all of the above:
```html
<body>
    <div id="app">
        <media-modal v-if="showMediaManager" @close="showMediaManager = false">
            <media-manager
                :is-modal="true"
                @close="showMediaManager = false"
            >
            </media-manager>
        </media-modal>
    
        <button @click="showMediaManager = true">
            Show Media Manager
        </button>
    </div>

    <script>
        new Vue({
        el: '#app',
            data: {
                showMediaManager: false,
            }
        });
    </script>
</body>
```

As well as providing all of the functionality that the normal `<media-manager>` component gives, when displayed within a modal window, buttons to close the window and `select` files are rendered.

## # Notification Events

So that you can make use of your projects existing notification system the Media Manager emits events than can be listened on using a separate `Vue` instance that is automatically created and added to the `window` with a name of `eventHub` (if `window.eventHub` doesn't already exist). 
The event emitted for notifications is called `media-manager-notification` and has the following signature : `(message, type, time)`. 

    - message: string
    - type : string
    - time : int

A listener can be added to either the `created()` method of your root `vue` instance or a component:

```html
<script>
    new Vue({
        el: '#app',
        data:{
            //...
        },
        created: function(){
            window.eventHub.$on('media-manager-notification', function (message, type, time) {
                // Your custom notifiction call here...
                console.log(message);
            });
        }
    });
</script>
```
## # Selected Item Events

When selecting an item through a Media Manager instance that has been opened within a modal window a new `select` event type is emitted.
Like notifications `select` will mean different things depending on the use of the application, there may even be a number of different uses cases for the Media Manager within an application.

To handle instances where different things may need to happen when a `select` event is triggered the Media Manager lets you define a custom `event` name to be emitted using the `selected-event-name` property:
```html
<media-modal v-if="showMediaManager" @close="showMediaManager = false">
    <media-manager
        :is-modal="true"
        :selected-event-name="selectedEventName"
        @close="showMediaManager = false"
    >
    </media-manager>
</media-modal>
 ```

When `select` is called a custom event is dispatched that can be listened for using Vue's `events` listeners.
The event name dispatched is dynamically generated by the `selected-event-name` property's value prefixed with `media-manager-selected-`
For example if the `selected-event-name` property was set to `editor` the event dispatched would be `media-manager-selected-editor` and we could handle the event using the `window.eventHub` as follows:
```javascript
<script>
    new Vue({
        el : 'body',
        data:{
            showMediaManager: false,
            selectedEventName: 'editor'
        },

        created: function(){
            window.eventHub.$on('media-manager-selected-editor', function (file) {
                // Do something with the file info...
                console.log(file.name);
                console.log(file.mimeType);
                console.log(file.relativePath);
                console.log(file.webPath);

                // Hide the Media Manager...
                this.showMediaManager = false;
            });
        }
    })
</script>
```
The prefix on the event names is to avoid / reduce any potential event names clashes on the event hub.
