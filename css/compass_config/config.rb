require 'compass/import-once/activate'
# Require any additional compass plugins here.

require 'compass-colors'
require 'color-schemer'
require 'fancy-buttons'
require 'sassy-buttons'
require "compass-recipes"
require "rgbapng"


# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "css"
sass_dir = "css"
images_dir = "images"
javascripts_dir = "js"
fonts_dir = "fonts"


# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :expanded


# development (default), production
environment = :development


# To enable relative paths to assets via compass helper functions. Uncomment:
#relative_assets = true


# To disable debugging comments that display the original location of your selectors. Uncomment:
# line_comments = false




##################################### help ##########################################

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass


# Usage: compass compile [path/to/project] [path/to/project/src/file.sass ...] [options]

# Description:
# compile project at the path specified or the current directory if not specified.

# Options:
#         --[no-]sourcemap             Generate a sourcemap during compilation.
#         --time                       Display compilation times.
#         --debug-info                 Turns on sass's debuging information
#         --no-debug-info              Turns off sass's debuging information
#     -r, --require LIBRARY            Require the given ruby LIBRARY before running commands.
#                                      This is used to access compass plugins without having a
#                                      project configuration file.
#     -l, --load FRAMEWORK_DIR         Load the framework or extensions found in the FRAMEWORK directory.
#     -L, --load-all FRAMEWORKS_DIR    Load all the frameworks or extensions found in the FRAMEWORKS_DIR directory.
#    -I, --import-path IMPORT_PATH     Makes files under the IMPORT_PATH folder findable by Sass's @import directive.
#     -q, --quiet                      Quiet mode.
#         --trace                      Show a full stacktrace on error
#         --force                      Allows compass to overwrite existing files.
#         --boring                     Turn off colorized output.
#     -c, --config CONFIG_FILE         Specify the location of the configuration file explicitly.
#         --app APP                    Tell compass what kind of application it is integrating with. E.g. rails
#         --app-dir PATH               The base directory for your application.
#         --sass-dir SRC_DIR           The source directory where you keep your sass stylesheets.
#        --css-dir CSS_DIR             The target directory where you keep your css stylesheets.
#         --images-dir IMAGES_DIR      The directory where you keep your images.
#         --javascripts-dir JS_DIR     The directory where you keep your javascripts.
#         --fonts-dir FONTS_DIR        The directory where you keep your fonts.
#     -e, --environment ENV            Use sensible defaults for your current environment.
#                                       One of: development (default), production
#     -s, --output-style STYLE         Select a CSS output mode.
#                                        One of: nested, expanded, compact, compressed
#         --relative-assets            Make compass asset helpers generate relative urls to assets.
#         --no-line-comments           Disable line comments.
#        --http-path HTTP_PATH         Set this to the root of your project when deployed
#        --generated-images-path GENERATED_IMAGES_PATH
#                                      The path where you generate your images
#     -?, -h, --help                   Show this message