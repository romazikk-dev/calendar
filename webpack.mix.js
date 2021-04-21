const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/vue/business_hours/app.js', 'public/js/business-hours.js').vue();
// mix.js('resources/js/vue/users_assignment/app.js', 'public/js/users-assignment.js').vue();
// mix.js('resources/js/vue/halls_assignment/app.js', 'public/js/halls-assignment.js').vue();
// mix.js('resources/js/vue/time_picker/app.js', 'public/js/time-picker.js').vue();
// mix.js('resources/js/vue/phone_chooser/app.js', 'public/js/phone-chooser.js').vue();
// mix.js('resources/js/vue/calendar/booking/app.js', 'public/js/calendar-booking.js').vue();
mix.js('resources/js/vue/dashboard/hall/index/app.js', 'public/js/dashboard/halls/halls-list.js').vue();

// mix.js('resources/js/ts/calendar-helper.ts', 'public/js/calendar-helper.js').webpackConfig({
mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: "ts-loader",
                exclude: /node_modules/
            }
        ]
    },
    resolve: {
        extensions: ["*", ".js", ".jsx", ".vue", ".ts", ".tsx"]
    }
});

mix.sass('resources/sass/dashboard.scss', 'public/css');

// mix.js('resources/js/app.js', 'public/js').vue();
// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');
