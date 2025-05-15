module.exports = function (grunt) {
  grunt.initConfig({
    sass: {
      dist: {
        files: {'css/manager.css': 'scss/main.scss'}
      }
    },
    watch: {
      css: {
        files: 'scss/**/*.scss',
        tasks: ['sass']
      },
      js: {
        files: 'js/**/*.js',
        tasks: ['uglify']
      }
    },
    uglify: {
      dist: {
        files: {
          'js/manager.js': [
            'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
            'js/preventresubmission.js'
          ]
        }
        
      }
    }
  })

  grunt.loadNpmTasks('grunt-contrib-sass')
  grunt.loadNpmTasks('grunt-contrib-watch')
  grunt.loadNpmTasks('grunt-contrib-uglify')
  grunt.registerTask('default', ['watch'])
}