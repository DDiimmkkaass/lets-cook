###
Base imports and vars
###
path = require 'path'
gulp = require 'gulp'
sass = require 'gulp-ruby-sass'
compass = require 'gulp-compass'
prefix = require 'gulp-autoprefixer'
util = require 'gulp-util'
concat = require 'gulp-concat'
uglify = require 'gulp-uglify'
coffee = require 'gulp-coffee'
imagemin = require 'gulp-imagemin'
minifyCSS = require 'gulp-minify-css'
plumber = require 'gulp-plumber'


# get the theme name
theme = path.basename(path.dirname(__dirname))

projectRoot = __dirname.slice(0, __dirname.indexOf('/resources/'))

console.log(projectRoot);

handleError = (err)->
  util.log err.toString()

dev_path =
  fonts: __dirname.concat('/fonts/**')
  vendor: __dirname.concat('/vendor/**')
  images: __dirname.concat('/img/**')
  coffee:__dirname.concat('/coffee/**.coffee')
  js:__dirname.concat('/js/**')
  sass: __dirname.concat('/sass/')

prod_path =
  fonts: projectRoot.concat('/public/assets/themes/' + theme + '/fonts/')
  vendor: projectRoot.concat('/public/assets/themes/' + theme + '/vendor/')
  images: projectRoot.concat('/public/assets/themes/' + theme + '/img/')
  js:     projectRoot.concat('/public/assets/themes/' + theme + '/js/')
  css:    projectRoot.concat('/public/assets/themes/' + theme + '/css/')


# Export tasks #
module.exports =
  default: [theme.concat('::css'), theme.concat('::coffee'), theme.concat('::purejs'), theme.concat('::images'), theme.concat('::fonts'), theme.concat('::vendor')]
  dev: [theme.concat('::css:dev'), theme.concat('::coffee:dev'), theme.concat('::purejs'), theme.concat('::images'), theme.concat('::fonts'), theme.concat('::vendor')]
  watch: [theme.concat('::css:watch'), theme.concat('::coffee:watch'), theme.concat('::purejs:watch'), theme.concat('::images:watch'), theme.concat('::fonts:watch'), theme.concat('::vendor:watch')]


# SASS #
gulp.task theme.concat("::css"), ->
  gulp.src(dev_path.sass.concat("*.sass"))
  .pipe(plumber())
  .pipe(sass({ style: 'expanded' }))
  .pipe(prefix())
  .pipe(minifyCSS(removeEmpty: true))
  .pipe(concat("styles.css"))
  .pipe(gulp.dest(prod_path.css))
  .on('error', plumber)


gulp.task theme.concat('::css:dev'), ->
  gulp.src(dev_path.sass.concat('*.sass'))
  .pipe(plumber())
  .pipe(sass({ style: 'expanded' }))
  .pipe(prefix())
  .pipe(concat('styles.css'))
  .pipe(gulp.dest(prod_path.css))
  .on('error', plumber)

gulp.task theme.concat('::css:watch'), ->
  gulp.watch dev_path.sass.concat('**/*.sass') , [theme.concat('::css:dev')]

# COFFEE #
gulp.task theme.concat('::coffee'), ->
  gulp.src(dev_path.coffee)
  .pipe(plumber())
  .pipe concat 'main.js'
  .pipe(coffee({bare: true}))
  .pipe(uglify({outSourceMap: true}))
  .pipe(gulp.dest(prod_path.js))
  .on('error', plumber)

gulp.task theme.concat('::coffee:dev'), ->
  gulp.src(dev_path.coffee)
  .pipe(plumber())
  .pipe concat 'main.js'
  .pipe(coffee({bare: true}))
  .pipe(gulp.dest(prod_path.js))
  .on('error', plumber)

gulp.task theme.concat('::coffee:watch'), ->
  gulp.watch dev_path.coffee, [theme.concat('::coffee:dev')]

# PUREJS #
gulp.task theme.concat('::purejs'), ->
  gulp.src(dev_path.js)
  .pipe(plumber())
  .pipe(uglify({outSourceMap: true}))
  .pipe(gulp.dest(prod_path.js))
  .on('error', plumber)

gulp.task theme.concat('::purejs:watch'), ->
  gulp.watch dev_path.js.concat('/*.js'), [theme.concat('::purejs')]


# IMAGES #
gulp.task theme.concat('::images'), ->
  gulp.src(dev_path.images)
  .pipe(plumber())
#  .pipe(imagemin())
  .pipe(gulp.dest(prod_path.images))
  .on('error', plumber)

gulp.task theme.concat('::images:watch'), ->
  gulp.watch dev_path.images , [theme.concat('::images')]

# FONTS #
gulp.task theme.concat('::fonts'), ->
  gulp.src(dev_path.fonts)
  .pipe(plumber())
  .pipe(gulp.dest(prod_path.fonts))
  .on('error', plumber)

gulp.task theme.concat('::fonts:watch'), ->
  gulp.watch dev_path.fonts , [theme.concat('::fonts')]

# VENDOR #
gulp.task theme.concat('::vendor'), ->
  gulp.src(dev_path.vendor)
  .pipe(plumber())
  .pipe(gulp.dest(prod_path.vendor))
  .on('error', plumber)

gulp.task theme.concat('::vendor:watch'), ->
  gulp.watch dev_path.vendor , [theme.concat('::vendor')]