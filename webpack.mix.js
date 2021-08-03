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

// mix.js('resources/js/vue/dashboard/timezone_picker/app.js', 'public/js/dashboard/timezone-picker.js').vue();
// mix.js('resources/js/vue/dashboard/time_picker/app.js', 'public/js/dashboard/time-picker.js').vue();
// mix.js('resources/js/vue/phone_picker/app.js', 'public/js/dashboard/phone-picker.js').vue();

// mix.js('resources/js/vue/calendar/booking/app.js', 'public/js/calendar-booking.js').vue();
mix.js('resources/js/vue/calendar_2/booking/app.js', 'public/js/calendar-booking-2.js').vue();
mix.js('resources/js/vue/dashboard/calendar/booking/app.js', 'public/js/dashboard/calendar-booking-admin.js').vue();

// mix.js('resources/js/vue/dashboard/hall/index/app.js', 'public/js/dashboard/halls/halls-list.js').vue();
// mix.js('resources/js/vue/dashboard/template/index/app.js', 'public/js/dashboard/template/templates-list.js').vue();
// 
// mix.js('resources/js/vue/dashboard/client/index/app.js', 'public/js/dashboard/client/clients-list.js').vue();


/*
 *  Worker mixes
 */
// mix.js('resources/js/vue/dashboard/worker/index/app.js', 'public/js/dashboard/worker/workers-list.js').vue();
// mix.js('resources/js/vue/dashboard/business_hours/app.js', 'public/js/dashboard/business-hours.js').vue();
// mix.js('resources/js/vue/dashboard/hall_assignment/app.js', 'public/js/dashboard/hall-assignment.js').vue();
// mix.js('resources/js/vue/dashboard/suspension/app.js', 'public/js/dashboard/suspension.js').vue();

// mix.js('resources/js/vue/dashboard/template_assignment/app.js', 'public/js/dashboard/template-assignment.js').vue();

// mix.js('resources/js/vue/dashboard/worker_assignment/app.js', 'public/js/dashboard/worker-assignment.js').vue();
// mix.js('resources/js/vue/dashboard/holidays/app.js', 'public/js/dashboard/holidays.js').vue();

//Setting
// mix.js('resources/js/vue/dashboard/setting/custom_fields/app.js', 'public/js/dashboard/setting/custom-fields.js').vue();

// mix.js('resources/js/vue/dashboard/setting/specifics_index/app.js', 'public/js/dashboard/setting/specifics-index.js').vue();
// mix.js('resources/js/vue/dashboard/specific_assignment/app.js', 'public/js/dashboard/specific-assignment.js').vue();


//TS apps
mix.ts('resources/js/ts/dashboard/calendar_helper/app.ts', 'public/js/dashboard/ts/calendar-helper.js');


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
