require "compass/import-once/activate"

sass_dir			= "app/assets/styles"
css_dir				= "public/assets/styles"
fonts_dir			= "public/assets/fonts"
images_dir			= "public/assets/images"
javascripts_dir		= "public/assets/scripts"

output_style		= :expanded
relative_assets		= true
line_comments		= false

# asset_cache_buster do |path, real_path|
# 	if File.exists?(real_path)
# 		pathname = Pathname.new(path)
# 		modified_time = File.mtime(real_path).strftime("%s")
# 		new_path = "%s/%s.%s%s" % [pathname.dirname, pathname.basename(pathname.extname), modified_time, pathname.extname]
# 		{:path => new_path, :query => nil}
# 	end
# end