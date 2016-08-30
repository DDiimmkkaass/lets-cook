###
Base imports and vars
###
path = require 'path'
gulp = require 'gulp'
prefix = require 'gulp-autoprefixer'
coffee = require 'gulp-coffee'
util = require 'gulp-util'
concat = require 'gulp-concat'
uglify = require 'gulp-uglify'
imagemin = require 'gulp-imagemin'
minifyCSS = require 'gulp-minify-css'
plumber = require 'gulp-plumber'
gulpLoadPlugins = require('gulp-load-plugins')
stylus = require('gulp-stylus')
nib = require('nib')
del = require('del')
wiredep = require('wiredep').stream

$ = gulpLoadPlugins()

# get the theme name
theme = path.basename(path.dirname(__dirname))

projectRoot = __dirname.slice(0, __dirname.indexOf('/resources/'))

console.log(projectRoot);

dev_path =
  fonts: __dirname.concat('/fonts/**')
  vendor: __dirname.concat('/vendor/**')
  images: __dirname.concat('/images/**')
  coffee:__dirname.concat('/coffee/**.coffee')
  js:__dirname.concat('/scripts/**')
  stylus: __dirname.concat('/styles/')

prod_path =
  fonts: projectRoot.concat('/public/assets/themes/' + theme + '/fonts/')
  vendor: projectRoot.concat('/public/assets/themes/' + theme + '/vendor/')
  images: projectRoot.concat('/public/assets/themes/' + theme + '/images/')
  js:     projectRoot.concat('/public/assets/themes/' + theme + '/js/')
  css:    projectRoot.concat('/public/assets/themes/' + theme + '/css/')


# Export tasks #
module.exports =
  default: [theme.concat('::css'), theme.concat('::coffee'), theme.concat('::purejs'), theme.concat('::images'), theme.concat('::fonts'), theme.concat('::vendor')]
  dev: [theme.concat('::css:dev'), theme.concat('::coffee:dev'), theme.concat('::purejs'), theme.concat('::images'), theme.concat('::fonts'), theme.concat('::vendor')]
  watch: [theme.concat('::css:watch'), theme.concat('::coffee:watch'), theme.concat('::purejs:watch'), theme.concat('::images:watch'), theme.concat('::fonts:watch'), theme.concat('::vendor:watch')]


# STYLUS #
gulp.task theme.concat("::css"), ->
  gulp.src(dev_path.stylus.concat("*.styl"))
  .pipe(plumber())
  .pipe(stylus
    use: [nib()]
  )
  .pipe(minifyCSS(removeEmpty: true))
  .pipe(concat("styles.css"))
  .pipe(gulp.dest(prod_path.css))
  .on('error', plumber)

gulp.task theme.concat('::css:dev'), ->
  gulp.src(dev_path.stylus.concat('*.styl'))
  .pipe(plumber())
  .pipe(stylus({ style: 'expanded' }))
  .pipe(prefix())
  .pipe(concat('styles.css'))
  .pipe(gulp.dest(prod_path.css))
  .on('error', plumber)

gulp.task theme.concat('::css:watch'), ->
  gulp.watch dev_path.stylus.concat('**/*.styl') , [theme.concat('::css:dev')]


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
  .pipe($.babel())
  .pipe(uglify({outSourceMap: true}))
  .pipe(gulp.dest(prod_path.js))
  .on('error', plumber)

gulp.task theme.concat('::purejs:watch'), ->
  gulp.watch dev_path.js.concat('/*.js'), [theme.concat('::purejs')]


# IMAGES #
gulp.task theme.concat('::images'), ->
  gulp.src(dev_path.images)
  .pipe(plumber())
  .pipe($.cache($.imagemin
    progressive: true,
    interlaced: true,
    svgoPlugins: [{cleanupIDs: false}]
  ))
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






































gulp = require('gulp')
gulpLoadPlugins = require('gulp-load-plugins')
stylus = require('gulp-stylus')
nib = require('nib')
browserSync = require('browser-sync')
del = require('del')
wiredep = require('wiredep').stream
$ = gulpLoadPlugins()
reload = browserSync.reload

lint = (files, options) ->
  gulp.src(files).pipe(reload(
    stream: true
    once: true)).pipe($.eslint(options)).pipe($.eslint.format()).pipe $.if(!browserSync.active, $.eslint.failAfterError())

gulp.task 'styles', ->
  gulp.src('app/styles/*.styl').pipe($.plumber()).pipe($.sourcemaps.init()).pipe(stylus(
    use: [nib()])).pipe($.sourcemaps.write()).pipe(gulp.dest('.tmp/styles')).pipe reload(stream: true)
gulp.task 'scripts', ->
  gulp.src('app/scripts/**/*.js').pipe($.plumber()).pipe($.sourcemaps.init()).pipe($.babel()).pipe($.sourcemaps.write('.')).pipe(gulp.dest('.tmp/scripts')).pipe reload(
    stream: true)
gulp.task 'lint', ->
  lint('app/scripts/**/*.js', fix: true).pipe gulp.dest('app/scripts')
gulp.task 'lint:test', ->
  lint('test/spec/**/*.js',
    fix: true
    env:
      mocha: true).pipe gulp.dest('test/spec/**/*.js')
gulp.task 'html', [
  'styles'
  'scripts'
], ->
  gulp.src('app/*.html').pipe($.useref(searchPath: [
    '.tmp'
    'app'
    '.'
  ])).pipe($.if('*.js', $.uglify())).pipe($.if('*.css', $.cssnano(
    safe: true
    autoprefixer: false))).pipe($.if('*.html', $.htmlmin())).pipe gulp.dest('dist')
gulp.task 'images', ->
  gulp.src('app/images/**/*').pipe($.cache($.imagemin(
    progressive: true
    interlaced: true
    svgoPlugins: [{cleanupIDs: false}]))).pipe gulp.dest('dist/images')
gulp.task 'fonts', ->
  gulp.src(require('main-bower-files')('**/*.{eot,svg,ttf,woff,woff2}', (err) ->
  ).concat('app/fonts/**/*')).pipe(gulp.dest('.tmp/fonts')).pipe gulp.dest('dist/fonts')
gulp.task 'extras', ->
  gulp.src([
    'app/*.*'
    '!app/*.html'
  ], dot: true).pipe gulp.dest('dist')
gulp.task 'clean', del.bind(null, [
  '.tmp'
  'dist'
])
gulp.task 'serve', [
  'styles'
  'scripts'
  'fonts'
], ->
  browserSync
    notify: false
    port: 9000
    server:
      baseDir: [
        '.tmp'
        'app'
      ]
      routes:
        '/bower_components': 'bower_components'
  gulp.watch([
    'app/*.html'
    'app/images/**/*'
    '.tmp/fonts/**/*'
  ]).on 'change', reload
  gulp.watch 'app/styles/**/*.styl', ['styles']
  gulp.watch 'app/scripts/**/*.js', ['scripts']
  gulp.watch 'app/fonts/**/*', ['fonts']
  gulp.watch 'bower.json', [
    'wiredep'
    'fonts'
  ]
  return
gulp.task 'serve:dist', ->
  browserSync
    notify: false
    port: 9000
    server:
      baseDir: ['dist']
  return
gulp.task 'serve:test', ['scripts'], ->
  browserSync
    notify: false
    port: 9000
    ui: false
    server:
      baseDir: 'test'
      routes:
        '/scripts': '.tmp/scripts'
        '/bower_components': 'bower_components'
  gulp.watch 'app/scripts/**/*.js', ['scripts']
  gulp.watch('test/spec/**/*.js').on 'change', reload
  gulp.watch 'test/spec/**/*.js', ['lint:test']
  return
# inject bower components
gulp.task 'wiredep', ->
  gulp.src('app/styles/*.styl').pipe(wiredep(ignorePath: /^(\.\.\/)+/)).pipe gulp.dest('app/styles')
  gulp.src('app/*.html').pipe(wiredep(ignorePath: /^(\.\.\/)*\.\./)).pipe gulp.dest('app')
  return
gulp.task 'build', [
  'lint'
  'html'
  'images'
  'fonts'
  'extras'
], ->
  gulp.src('dist/**/*').pipe $.size(
    title: 'build'
    gzip: true)
gulp.task 'default', ['clean'], ->
  gulp.start 'build'
  return

# ---
# generated by js2coffee 2.2.0