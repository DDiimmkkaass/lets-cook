###
Base imports and vars
###
glob = require 'glob'
gulp = require 'gulp'
util = require 'gulp-util'

buildFiles = './resources/themes/*/assets/build.coffee'

###
Get tasks from all themes
MUST be defined before tasks
###
getTasks = (taskType)->
    taskType = taskType or 'default'
    taskList = []
    files = glob.sync buildFiles
    if files.length == 0
        util.log util.colors.red("No build files found!")
    else
        util.log "using build files:"
        files.forEach (file)->
            util.log file
        files.forEach (file)->
            buildTask = require(file)[taskType]
            if buildTask
                if typeof buildTask == 'string'
                    taskList.push buildTask
                else if buildTask instanceof Array
                    taskList = taskList.concat buildTask
    return taskList


###
Tasks
###
gulp.task 'default', getTasks('default')
gulp.task 'dev', getTasks('dev')
gulp.task 'watch', getTasks('watch')